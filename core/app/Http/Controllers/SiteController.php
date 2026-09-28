<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller {
    public function index() {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle   = 'Home';
        $sections    = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        $sellCurrencies = Currency::enabled()->availableForSell()->orderBy('name')->get();
        $buyCurrencies  = Currency::enabled()->availableForBuy()->orderBy('name')->get();

        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'sellCurrencies', 'buyCurrencies'));
    }

    public function pages($slug) {
        $page        = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle   = $page->name;
        $sections    = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact() {
        $pageTitle   = "Contact Us";
        $user        = auth()->user();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request) {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];
        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug) {
        $policy      = Frontend::where('tempname', activeTemplateName())->where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle   = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function blog() {
        $pageTitle   = "Blogs";
        $blogs       = Frontend::where('tempname', activeTemplateName())->where('data_keys', 'blog.element')->latest()->paginate(getPaginate(12));
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::blog', compact('blogs', 'pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function blogDetails($slug) {
        $blog  = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $blogs = Frontend::where('data_keys', 'blog.element')
            ->where('slug', '!=', $slug)
            ->where('tempname', activeTemplateName())
            ->latest()
            ->take(5)
            ->get();
        $pageTitle   = 'Blog Details';
        $seoContents = $blog->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;

        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'blogs'));
    }

    public function cookieAccept() {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy() {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null) {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance() {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function trackExchange(Request $request) {

        $validator = Validator::make($request->all(), [
            'exchange_id' => 'required|exists:exchanges,exchange_id',
        ], [
            'exchange_id.exists' => "The provided exchange ID is invalid",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error'   => $validator->errors()->all(),
            ]);
        }

        $exchange = Exchange::where('exchange_id', $request->exchange_id)->first();
        $html     = view('Template::user.exchange.exchange_tracking', compact('exchange'))->render();

        if ($exchange) {
            return response()->json([
                'success' => true,
                'html'    => $html,
            ]);
        }
    }

    public function subscribe(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.unique' => "You have already subscribed",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => $validator->errors(),
                'success' => false,
            ]);
        }
        $subscribe        = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();

        return response()->json([
            'message' => "Thank you for subscribing us",
            'success' => true,
        ]);
    }

    public function faq() {
        $pageTitle   = "Frequently Asked Question";
        $faqs        = Frontend::where('tempname', activeTemplateName())->where('data_keys', 'faq.element')->latest()->get();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'faq')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::faq', compact('faqs', 'pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function downloadPdf($hash, $id) {
        try {
            $userId = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }

        $exchange = Exchange::where('user_id', auth()->id())
            ->orderBy('id', 'DESC')
            ->where('id', $id)
            ->firstOrFail();

        $user      = User::where('id', $userId)->firstOrFail();
        $pageTitle = "Download Exchange";
        $pdf       = PDF::loadView('partials.pdf', compact('pageTitle', 'user', 'exchange'));
        $fileName  = $exchange->exchange_id . '_' . time();

        return $pdf->download($fileName . '.pdf');

        return route('user.exchange.invoice', ['id' => $exchange->exchange_id, 'type' => 'download']);
    }
}
