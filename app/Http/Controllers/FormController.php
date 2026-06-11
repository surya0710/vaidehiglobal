<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequirementMail;
use App\Mail\PopupFormMail;

class FormController extends Controller
{
    public function contactForm(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'companyName' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'email' => 'required|email',
            'productCluster' => 'required|string',
            'requirementDetails' => 'required|min:30',
            'estimatedVolume' => 'nullable|string',
            'targetTimeline' => 'nullable|string',
        ]);

        // Send Mail
        Mail::to('info@vaidehiglobal.com')->send(new RequirementMail($validated));

        return response()->json([
            'message' => 'Requirement submitted successfully!'
        ]);
    }

    public function popupForm(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|min:30',
        ]);

        Mail::to('info@vaidehiglobal.com')
            ->send(new PopupFormMail($validated));

        return response()->json([
            'message' => 'Form submitted successfully!'
        ]);
    }
}
