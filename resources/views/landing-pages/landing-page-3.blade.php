<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Editor</title>

    <link rel="stylesheet" href="{{ asset('landing-page/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/assets/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/assets/css/spectrum.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/landing-page-3/css/style.css') }}">

    @if (isset($enableEdit))
        <link rel="stylesheet" href="{{ asset('landing-page/assets/css/text-editor.css') }}">
    @else
        <style>
            .section-off-on-buttons span,
            .card_list_append_button,
            .card_list_delete_button{
                display: none;
            }
        </style>
    @endif
</head>

<body>
    @php
        $content = json_decode($landingPage->content ?? '[]', true);
        $style = json_decode($landingPage->style ?? '[]', true);

        $main_text = $content['main_text'] ?? [
            'ফিড ছাড়া মাছ চাষ, ৩ মাসে মাছের ওজন হবে',
            '২ কেজির বেশি ।',
            'একজন মাছ চাষী সাধারণত ফিডের উপর নির্ভরশীল , ফিড ব্যবহারের ফলে মাছের আশানরূপ গ্রোথ আসে না । মাছ বিক্রির টাকায় ফিডের দাম হয় না । ফলে মাছ চাষীর লস হয় । যদি সেফ ফিশ ও গ্রোথ ফাস্টার ব্যাবহার করা হয় তাহলে ফিড প্রয়োজন নেই । মাছ চাষী শতভাগ লাভবান হবে ।',
            'সেফ ফিশ ও গ্রোথ ফাস্টার ব্যবহার বিধিঃ',
            'এই গরমে মাছ সুস্থ্য রাখতে এই প্রডাক্ট ব্যবহার করুন । এই প্রডাক্ট ব্যবহার করলে খাবার দিতে হয় না । তিন মাসে মাছের ওজন দুই কেজির বেশি হয় । চুন, লবণ, সার ,পটাশ ইত্যাদি ব্যবহার করতে হবে না । ফিড ছাড়া মাছ চাষ। মাছের গ্রোথ, টেম্পার , সাইজ ,কালার এভরিথিং স্মার্ট। কোন টেনশন নাই কিভাবে ফিড ছাড়া মাছ চাষ।',
            'সেফ ফিশ ও গ্রোথ ফাস্টার কেন ব্যাবহার করবেন?',
            'অর্ডার করতে সঠিক তথ্য দিয়ে নিচের ফর্ম পূরন করুন',
        ];

        $subtext_one = $content['subtext_one'] ?? [
            'প্রথম ডোজঃ (সেফ ফিশ)',
            'প্রতি শতক জলাকারে 100 গ্রাম সেফ ফিশ পানিতে ভাল করে মিশিয়ে পুরো পুকুরে ছিটিয়ে দিতে হবে ।',
            '২য় ডোজঃ তিন দিন পরে (গ্রোথ ফাস্টার)',
            'প্রতি শতক জলাকারে 100 গ্রাম গ্রোথ ফাস্টার পানিতে ভাল করে মিশিয়ে পুরো পুকুরে ছিটিয়ে দিতে হবে ।',
            'তিন মাসের মধ্যে আর কোন কিছু ব্যবহার করতে হবে না ।',
        ];

        $subtext_two = $content['subtext_two'] ?? [
            'একজন মাছ চাষী সাধারণত ফিডের উপর নির্ভরশীল , ফিড ব্যবহারের ফলে মাছের আশানরূপ গ্রোথ আসে না । মাছ বিক্রির টাকায় ফিডের দাম হয় না । ফলে মাছ চাষীর লস হয় । যদি সেফ ফিশ ও গ্রোথ ফাস্টার ব্যাবহার করা হয় তাহলে ফিড প্রয়োজন নেই । মাছ চাষী শতভাগ লাভবান হবে ।',
            'সেফ ফিশ ও গ্রোথ ফাস্টার ব্যবহার করলে ৩ মাসে মাছের ওজন ৪ কেজির বেশি হয় । তিন মাসের মধ্যে আর কোন খাবার দিতে হবে না সাদা মাছের ক্ষেত্রে ।',
            'পানির দ্রভিভুত গাস কমায় । অক্সিজেন সরবরাহ বারায় । পানিতে প্লান্টন তৈরি করে । পানির পিএইচ ও এমোনিয়া নিয়ন্ত্রণে রাখে । পানি ও মাটি দূষণ মুক্ত রাখে । মাছের রোগ বালাই দমন করে । পুকুরের পরিবেশ মাছ চাষের উপযোগী করে তোলে । মাছ স্বাভাবিক থেকে ৬০ % বেশি বড় হবে । ৭০% খাবার কম লাগবে ।',
            'পুকুরে তিন স্তরে খাবার তৈরি করে , পানির তলদেশে বা কাদামাটিতে, পানির উপরে এবং মধ্য স্তরে । যার ফলে আপনাকে আর অতিরিক্ত খাবার দিতে হবে না ।',
            'সকল প্রকার মাছ চাষে, যে কোন সময় ব্যবহার করা যায় ।',
        ];

        $videos = $content['videos'] ?? [
            1 => 'https://www.youtube.com/embed/npqaq1Pnwrk',
            2 => 'https://www.youtube.com/embed/4w2PmVFegC8'
        ];

        $images = $content['images'] ?? [
            1 => 'landing-page/' . optional($landingTheme)->slug . '/images/maxresdefault-1-1024x576-1.jpg',
            2 => 'landing-page/' . optional($landingTheme)->slug . '/images/sddefault-1.jpg'
        ];

        $section_status = $content['section_status'] ?? [
            'section_1' => 'section_on',
            'section_2' => 'section_on',
            'section_3' => 'section_on',
            'section_4' => 'section_on',
            'section_5' => 'section_on'
        ];
    @endphp

    <!-- Banner Section -->
    <section class="banner-section {{empty($enableEdit) && $section_status['section_1'] !== 'section_on' ? 'd-none' : ''}}">
        <div class="section-off-on-buttons">
            <input type="checkbox" name="section_one" class="section_switch" hidden="hidden" data-section="1"
                    id="section_one_switch" {{ $section_status['section_1'] == 'section_on' ? 'checked' : '' }}>
            <label class="switch" for="section_one_switch"></label><span>1</span>
        </div>
        <div class="container {{ $section_status['section_1'] ?? '' }}">
            <div class="banner">
                <h3 class="banner-title">
                    <span class="lp_text_editable get_main_text_0"  style="{{ $style['get_style_0'] ?? '' }}">
                        {{ $main_text[0] ?? '' }}
                    </span>
                    <span class="lp_text_editable get_main_text_1" style="{{ $style['get_style_1'] ?? '' }}">
                        {{ $main_text[1] ?? '' }}
                    </span>
                </h3>
                <p class="banner-subtitle lp_text_editable get_main_text_2" style="{{ $style['get_style_2'] ?? '' }}">
                    {{ $main_text[2] ?? '' }}
                </p>
                <div class="banner-video lp_video_editable" data-video_id="1">
                    <iframe src="{{ $videos[1] ?? '' }}" frameborder="0" width="100%" height="360"></iframe>
                </div>
                <a href="#order" class="order-button">অর্ডার করতে চাই</a>
            </div>
        </div>
        <div class="banner-bottom-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 283.5 27.8" preserveAspectRatio="none">
                <path class="elementor-shape-fill" d="M283.5,9.7c0,0-7.3,4.3-14,4.6c-6.8,0.3-12.6,0-20.9-1.5c-11.3-2-33.1-10.1-44.7-5.7	s-12.1,4.6-18,7.4c-6.6,3.2-20,9.6-36.6,9.3C131.6,23.5,99.5,7.2,86.3,8c-1.4,0.1-6.6,0.8-10.5,2c-3.8,1.2-9.4,3.8-17,4.7	c-3.2,0.4-8.3,1.1-14.2,0.9c-1.5-0.1-6.3-0.4-12-1.6c-5.7-1.2-11-3.1-15.8-3.7C6.5,9.2,0,10.8,0,10.8V0h283.5V9.7z M260.8,11.3	c-0.7-1-2-0.4-4.3-0.4c-2.3,0-6.1-1.2-5.8-1.1c0.3,0.1,3.1,1.5,6,1.9C259.7,12.2,261.4,12.3,260.8,11.3z M242.4,8.6	c0,0-2.4-0.2-5.6-0.9c-3.2-0.8-10.3-2.8-15.1-3.5c-8.2-1.1-15.8,0-15.1,0.1c0.8,0.1,9.6-0.6,17.6,1.1c3.3,0.7,9.3,2.2,12.4,2.7	C239.9,8.7,242.4,8.6,242.4,8.6z M185.2,8.5c1.7-0.7-13.3,4.7-18.5,6.1c-2.1,0.6-6.2,1.6-10,2c-3.9,0.4-8.9,0.4-8.8,0.5	c0,0.2,5.8,0.8,11.2,0c5.4-0.8,5.2-1.1,7.6-1.6C170.5,14.7,183.5,9.2,185.2,8.5z M199.1,6.9c0.2,0-0.8-0.4-4.8,1.1	c-4,1.5-6.7,3.5-6.9,3.7c-0.2,0.1,3.5-1.8,6.6-3C197,7.5,199,6.9,199.1,6.9z M283,6c-0.1,0.1-1.9,1.1-4.8,2.5s-6.9,2.8-6.7,2.7	c0.2,0,3.5-0.6,7.4-2.5C282.8,6.8,283.1,5.9,283,6z M31.3,11.6c0.1-0.2-1.9-0.2-4.5-1.2s-5.4-1.6-7.8-2C15,7.6,7.3,8.5,7.7,8.6	C8,8.7,15.9,8.3,20.2,9.3c2.2,0.5,2.4,0.5,5.7,1.6S31.2,11.9,31.3,11.6z M73,9.2c0.4-0.1,3.5-1.6,8.4-2.6c4.9-1.1,8.9-0.5,8.9-0.8	c0-0.3-1-0.9-6.2-0.3S72.6,9.3,73,9.2z M71.6,6.7C71.8,6.8,75,5.4,77.3,5c2.3-0.3,1.9-0.5,1.9-0.6c0-0.1-1.1-0.2-2.7,0.2	C74.8,5.1,71.4,6.6,71.6,6.7z M93.6,4.4c0.1,0.2,3.5,0.8,5.6,1.8c2.1,1,1.8,0.6,1.9,0.5c0.1-0.1-0.8-0.8-2.4-1.3	C97.1,4.8,93.5,4.2,93.6,4.4z M65.4,11.1c-0.1,0.3,0.3,0.5,1.9-0.2s2.6-1.3,2.2-1.2s-0.9,0.4-2.5,0.8C65.3,10.9,65.5,10.8,65.4,11.1	z M34.5,12.4c-0.2,0,2.1,0.8,3.3,0.9c1.2,0.1,2,0.1,2-0.2c0-0.3-0.1-0.5-1.6-0.4C36.6,12.8,34.7,12.4,34.5,12.4z M152.2,21.1	c-0.1,0.1-2.4-0.3-7.5-0.3c-5,0-13.6-2.4-17.2-3.5c-3.6-1.1,10,3.9,16.5,4.1C150.5,21.6,152.3,21,152.2,21.1z">
                </path>
                <path class="elementor-shape-fill" d="M269.6,18c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C267.7,18.8,269.7,18,269.6,18z">
                </path>
                <path class="elementor-shape-fill" d="M227.4,9.8c-0.2-0.1-4.5-1-9.5-1.2c-5-0.2-12.7,0.6-12.3,0.5c0.3-0.1,5.9-1.8,13.3-1.2	S227.6,9.9,227.4,9.8z">
                </path>
                <path class="elementor-shape-fill" d="M204.5,13.4c-0.1-0.1,2-1,3.2-1.1c1.2-0.1,2,0,2,0.3c0,0.3-0.1,0.5-1.6,0.4	C206.4,12.9,204.6,13.5,204.5,13.4z">
                </path>
                <path class="elementor-shape-fill" d="M201,10.6c0-0.1-4.4,1.2-6.3,2.2c-1.9,0.9-6.2,3.1-6.1,3.1c0.1,0.1,4.2-1.6,6.3-2.6	S201,10.7,201,10.6z">
                </path>
                <path class="elementor-shape-fill" d="M154.5,26.7c-0.1-0.1-4.6,0.3-7.2,0c-7.3-0.7-17-3.2-16.6-2.9c0.4,0.3,13.7,3.1,17,3.3	C152.6,27.5,154.6,26.8,154.5,26.7z">
                </path>
                <path class="elementor-shape-fill" d="M41.9,19.3c0,0,1.2-0.3,2.9-0.1c1.7,0.2,5.8,0.9,8.2,0.7c4.2-0.4,7.4-2.7,7-2.6	c-0.4,0-4.3,2.2-8.6,1.9c-1.8-0.1-5.1-0.5-6.7-0.4S41.9,19.3,41.9,19.3z">
                </path>
                <path class="elementor-shape-fill" d="M75.5,12.6c0.2,0.1,2-0.8,4.3-1.1c2.3-0.2,2.1-0.3,2.1-0.5c0-0.1-1.8-0.4-3.4,0	C76.9,11.5,75.3,12.5,75.5,12.6z">
                </path>
                <path class="elementor-shape-fill" d="M15.6,13.2c0-0.1,4.3,0,6.7,0.5c2.4,0.5,5,1.9,5,2c0,0.1-2.7-0.8-5.1-1.4	C19.9,13.7,15.7,13.3,15.6,13.2z">
                </path>
            </svg>
        </div>
    </section>

    <!-- Rules Section -->
    <section class="rules-section {{empty($enableEdit) && $section_status['section_2'] !== 'section_on' ? 'd-none' : ''}}">
        <div class="section-off-on-buttons">
            <input type="checkbox" name="section_two" class="section_switch" hidden="hidden" data-section="2"
                id="section_two_switch" {{ $section_status['section_2'] == 'section_on' ? 'checked' : '' }}>
            <label class="switch" for="section_two_switch"></label></label><span>2</span>
        </div>
        <div class="container {{ $section_status['section_2'] ?? '' }}">
            <div class="rules-heading">
                <h2 class="lp_text_editable get_main_text_3" style="{{ $style['get_style_3'] ?? '' }}">
                    {{ $main_text[3] ?? '' }}
                </h2>
            </div>
            <div class="rules">
                <div class="row">
                    <div class="col-md-6 col-12 mb-3 card-list card_list_one">
                        <ul>
                            @if (count($subtext_one) > 0)
                                @foreach ($subtext_one as $key => $sub_text_one)
                                    <li class="rule-item">
                                        <span>
                                            <svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path>
                                            </svg>
                                        </span>
                                        <span class="lp_text_editable subtext_one subtext_one_{{ $key }}" style="{{ $style['subtext_one_' . $key] ?? '' }}">
                                            {{ $sub_text_one }}
                                        </span>
                                        <button class="card_list_delete_button" data-type="1" data-key="{{ $key }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M18 6l-12 12" />
                                                <path d="M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </li>
                                @endforeach
                            @endif
                            <button class="card_list_append_button" data-type="1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Add Item
                            </button>
                        </ul>
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <div class="usages-rules-video lp_video_editable" data-video_id="2">
                            <iframe src="{{ $videos[2] ?? '' }}" frameborder="0" width="100%" height="300"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Section -->
    <section class="success-section {{empty($enableEdit) && $section_status['section_3'] !== 'section_on' ? 'd-none' : ''}}">
        <div class="section-off-on-buttons">
            <input type="checkbox" name="section_three" class="section_switch" hidden="hidden" data-section="3"
                id="section_three_switch" {{ $section_status['section_3'] == 'section_on' ? 'checked' : '' }}>
            <label class="switch" for="section_three_switch"></label></label><span>3</span>
        </div>
        <div class="container {{ $section_status['section_3'] ?? '' }}">
            <div class="success">
                <div class="success-heading">
                    <p class="lp_text_editable get_main_text_4" style="{{ $style['get_style_4'] ?? '' }}">
                        {{ $main_text[4] ?? '' }}
                    </p>
                </div>
                <div class="success-image">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3 lp_image_editable" data-image_id="1">
                            <img src="{{ asset($images[1] ) }}"
                                alt="" class="success-image-one img-fluid">
                        </div>
                        <div class="col-md-6 col-12 mb-3 lp_image_editable" data-image_id="2">
                            <img src="{{ asset($images[2] ) }}"
                                alt="" class="success-image-two img-fluid">
                        </div>
                    </div>
                </div>
                <a href="#order" class="order-button">অর্ডার করতে চাই</a>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="why-use-section {{empty($enableEdit) && $section_status['section_4'] !== 'section_on' ? 'd-none' : ''}}">
        <div class="section-off-on-buttons">
            <input type="checkbox" name="section_four" class="section_switch" hidden="hidden" data-section="4"
                id="section_four_switch" {{ $section_status['section_4'] == 'section_on' ? 'checked' : '' }}>
            <label class="switch" for="section_four_switch"></label><span>4</span>
        </div>
        <div class="container {{ $section_status['section_4'] ?? '' }}">
            <div class="why-use">
                <div class="why-use-heading">
                    <h2 class="lp_text_editable get_main_text_5" style="{{ $style['get_style_5'] ?? '' }}">
                        {{ $main_text[5] ?? '' }}
                    </h2>
                </div>
                <div class="why-use-content card-list card_list_two">
                    <ul>
                        @if (count($subtext_two) > 0)
                            @foreach ($subtext_two as $key => $sub_text_two)
                                <li class="why-use-item">
                                    <span>
                                        <svg aria-hidden="true" class="e-font-icon-svg e-far-hand-point-right" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M428.8 137.6h-86.177a115.52 115.52 0 0 0 2.176-22.4c0-47.914-35.072-83.2-92-83.2-45.314 0-57.002 48.537-75.707 78.784-7.735 12.413-16.994 23.317-25.851 33.253l-.131.146-.129.148C135.662 161.807 127.764 168 120.8 168h-2.679c-5.747-4.952-13.536-8-22.12-8H32c-17.673 0-32 12.894-32 28.8v230.4C0 435.106 14.327 448 32 448h64c8.584 0 16.373-3.048 22.12-8h2.679c28.688 0 67.137 40 127.2 40h21.299c62.542 0 98.8-38.658 99.94-91.145 12.482-17.813 18.491-40.785 15.985-62.791A93.148 93.148 0 0 0 393.152 304H428.8c45.435 0 83.2-37.584 83.2-83.2 0-45.099-38.101-83.2-83.2-83.2zm0 118.4h-91.026c12.837 14.669 14.415 42.825-4.95 61.05 11.227 19.646 1.687 45.624-12.925 53.625 6.524 39.128-10.076 61.325-50.6 61.325H248c-45.491 0-77.21-35.913-120-39.676V215.571c25.239-2.964 42.966-21.222 59.075-39.596 11.275-12.65 21.725-25.3 30.799-39.875C232.355 112.712 244.006 80 252.8 80c23.375 0 44 8.8 44 35.2 0 35.2-26.4 53.075-26.4 70.4h158.4c18.425 0 35.2 16.5 35.2 35.2 0 18.975-16.225 35.2-35.2 35.2zM88 384c0 13.255-10.745 24-24 24s-24-10.745-24-24 10.745-24 24-24 24 10.745 24 24z"></path>
                                        </svg>
                                    </span>
                                    <span class="lp_text_editable subtext_two subtext_two_{{ $key }}" style="{{ $style['subtext_two_' . $key] ?? '' }}">
                                        {{ $sub_text_two }}
                                    </span>
                                    <button class="card_list_delete_button" data-type="2" data-key="{{ $key }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M18 6l-12 12" />
                                            <path d="M6 6l12 12" />
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        @endif
                        <button class="card_list_append_button" data-type="2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add Item
                        </button>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Section -->
    <section class="order-section {{empty($enableEdit) && $section_status['section_5'] !== 'section_on' ? 'd-none' : ''}}" id="order">
        <div class="section-off-on-buttons">
            <input type="checkbox" name="section_four" class="section_switch" hidden="hidden" data-section="5"
                id="section_five_switch" {{ $section_status['section_5'] == 'section_on' ? 'checked' : '' }}>
            <label class="switch" for="section_five_switch"></label><span>5</span>
        </div>
        <div class="container">
            <div class="order-content">
                <div class="order-heading">
                    <h2 class="lp_text_editable get_main_text_6" style="{{ $style['get_style_6'] ?? '' }}">
                        {{ $main_text[6] ?? '' }}
                    </h2>
                </div>
                <form action="{{ route('landing.place-order') }}" method="post" id="order-form">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="product-details">
                                <h3 class="order-title">Your Products</h3>
                            </div>
                            <div class="row">
                                @if (!empty($products))
                                    @forelse ($products as $item)
                                        @php
                                            $cart = \Cart::getContent();
                                            $cartItem = $cart->where('name', $item->name)->first() ?? $cart->where('id', $item->sku)->first();

                                            $isChecked = $cartItem ? 'checked' : '';
                                            $quantity = $cartItem ? $cartItem->quantity : 1;
                                            $basePrice = $item->sale_price ?? $item->regular_price ?? 0;

                                            $selectedAttributeIds = [];
                                            if ($cartItem && $item->has_variant) {
                                                foreach ($cartItem->attributes as $attrValue) {
                                                    // Extract numeric IDs from strings like "18-2"
                                                    $parts = explode('-', $attrValue);
                                                    foreach ($parts as $part) {
                                                        if (is_numeric($part)) $selectedAttributeIds[] = (int)$part;
                                                    }
                                                }
                                            }
                                            // dd($cartItem->attributes, $selectedAttributeIds);
                                        @endphp

                                        <div class="col-md-6 col-12 mb-2">
                                            <div class="product-card" data-product-id="{{ $item->id }}" style="border: {{ $isChecked ? '2px solid #6EC1E4;':'' }}  ">

                                                <input type="hidden" class="product-id" value="{{ $item->id }}">
                                                <input type="hidden" class="base-price" value="{{ $basePrice }}">
                                                <input type="hidden" class="variant-price" value="0">
                                                <input type="hidden" class="variant-sku" value="{{ $cartItem ? $cartItem->id : ($item->sku ?? $item->id) }}">
                                                <input type="hidden" class="variant-name-value" value="">

                                                <div class="product-item">
                                                    <div class="product-input">
                                                        <input class="form-check-input product-toggle" type="checkbox" name="product_ids[]" value="{{ $item->id }}" {{ $isChecked }}>
                                                    </div>

                                                    <div class="product-image my-2">
                                                        <img src="{{ $item->get_thumb ? asset($item->get_thumb->file_url) : asset('frontEnd/assets/images/image.png') }}"
                                                            alt="{{ $item->name }}" class="img-fluid" style="max-width: 100px;">
                                                    </div>

                                                    <div class="product-info">
                                                        <div class="product-name-quantity">
                                                            <span class="name product-name fw-bold">{{ $item->name }}</span>
                                                        </div>

                                                        <div class="product-quantity-price d-flex align-items-center gap-3 mt-2">
                                                            <div class="quantity d-flex border rounded">
                                                                <span class="quantity-minus px-3 py-1 border-end" style="cursor:pointer">-</span>
                                                                <input type="number" class="quantity-input border-0 text-center"
                                                                    name="quantity[{{ $item->id }}]" value="{{ $quantity }}" min="1" readonly style="width: 50px;">
                                                                <span class="quantity-plus px-3 py-1 border-start" style="cursor:pointer">+</span>
                                                            </div>
                                                            <div class="price fw-bold text-primary">
                                                                {{ $web_settings?->currency_sign ?? '৳' }} <span class="line-price">0.00</span>
                                                            </div>
                                                        </div>

                                                        {{-- Variant Selection Logic --}}
                                                        @if ($item->has_variant && count($item->get_variants ?? []) > 0)
                                                            @foreach ($item->get_attribute_with_items() as $attributeBlock)
                                                                @php
                                                                    $attribute = $attributeBlock['attribute'];
                                                                    $attr_items = $attributeBlock['items'];
                                                                @endphp
                                                                <div class="variant-group mt-2">
                                                                    <label class="variant-label fw-bold d-block mb-1">
                                                                        {{ ucfirst($attribute->name) }}: <span class="selected-variant-display text-muted small"></span>
                                                                    </label>
                                                                    <div class="variant-items d-flex flex-wrap gap-2">
                                                                        @foreach ($attr_items as $key => $v_item)
                                                                            @php
                                                                                // Check if this specific item is selected in the cart
                                                                                $isThisSelected = in_array($v_item->attribute_item_id, $selectedAttributeIds);
                                                                                if (empty($selectedAttributeIds) && $key == 0) {
                                                                                    $isThisSelected = true;
                                                                                }
                                                                            @endphp
                                                                            <label class="variant-item {{ $isThisSelected ? 'active border-primary' : '' }} border rounded" style="cursor:pointer">
                                                                                <input type="radio"
                                                                                    class="attr_checkbox d-none"
                                                                                    name="attribute_item_id[{{ $item->id }}][{{ $attribute->id }}]"
                                                                                    value="{{ $v_item->attribute_item_id }}"
                                                                                    data-variant-name="{{ $v_item->name }}"
                                                                                    {{ $isThisSelected ? 'checked' : '' }}>

                                                                                @if (($attribute->is_image ?? false) && $v_item->item_image)
                                                                                    <img src="{{ asset($v_item->item_image->file_url) }}" width="30" height="30" alt="{{ $v_item->name }}">
                                                                                @else
                                                                                    <span class="px-2">{{ ucfirst($v_item->name) }}</span>
                                                                                @endif
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center">No products found.</div>
                                    @endforelse
                                @else
                                    <div class="col-12 text-center">No products found.</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-2">
                            <div class="billing-details">
                                <h3 class="order-title">Billing details</h3>

                                <div class="col-12 mb-4">
                                    <label class="form-label">আপনার নাম <span>*</span></label>
                                    <input type="text" name="customer_name"
                                        class="form-control customer_name @error('customer_name') is-invalid @enderror"
                                        value="{{ old('customer_name') }}"
                                        placeholder="আপনার নাম লিখুন" required>
                                    @error('customer_name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">আপনার মোবাইল নাম্বার <span>*</span></label>
                                    <input type="text" name="customer_phone"
                                        class="form-control customer_phone @error('customer_phone') is-invalid @enderror"
                                        value="{{ old('customer_phone') }}"
                                        maxlength="11" minlength="11"
                                        placeholder="আপনার 11 ডিজিটের মোবাইল নাম্বার" required>
                                    @error('customer_phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <div class="phone-error text-danger small" style="display:none;">সঠিক মোবাইল নাম্বার দিন।</div>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">আপনার ঠিকানা <span>*</span></label>
                                    <textarea name="customer_address"
                                            class="form-control customer_address @error('customer_address') is-invalid @enderror"
                                            placeholder="বাসা নং, রোড নং, গ্রাম, উপজেলা, জেলা"
                                            minlength="10" required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" id="submit_btn" class="order-place-button btn btn-primary btn-lg w-100">
                                    🛒 Place Order {{ $web_settings?->currency_sign ?? '৳' }} 0.00
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="order-details">
                                <h3 class="order-title">Your order</h3>
                                <div class="order_review">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-summary-body"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="fs-5">Total</th>
                                                <th class="fs-5 text-primary">{{ $web_settings?->currency_sign ?? '৳' }} <span id="summary-total">0.00</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    document.addEventListener('click', function (e) {

                        const card = e.target.closest('.product-card');
                        if (!card) return;

                        // ❌ Ignore these areas
                        if (
                            e.target.closest('.quantity') ||
                            e.target.closest('.variant-group') ||
                            e.target.closest('input') ||
                            e.target.closest('label')
                        ) {
                            return;
                        }

                        const checkbox = card.querySelector('.product-toggle');
                        if (!checkbox) return;

                        checkbox.checked = !checkbox.checked;

                        // ✅ jQuery change trigger
                        $(checkbox).trigger('change');
                    });
                </script>
                <script>

                    $(document).ready(function() {
                        const currency = "{{ $web_settings?->currency_sign ?? '৳' }}";

                        /* 1. Initialize variant selection on page load */
                        $('.product-card').each(function() {
                            let $card = $(this);
                            // Set active class for checked variant items
                            $card.find('.variant-item input:checked').each(function() {
                                $(this).closest('.variant-item').addClass('active border-primary');
                            });

                            // Initialize variant name display
                            updateVariantDisplay($card);
                        });

                        /* 1. Toggle Checkbox (Add/Remove) */
                        $(document).on('change', '.product-toggle', function() {
                            let $card = $(this).closest('.product-card');
                            let isChecked = $(this).is(':checked');

                            if (isChecked) {
                                $card.css('border', '2px solid #6EC1E4');
                                addToCart($card);
                            } else {
                                $card.css('border', '');
                                removeFromCart($card);
                            }
                        });

                        /* 2. Variant Selection - FIXED */
                        $(document).on('click', '.variant-item', function(e) {
                            e.preventDefault();
                            let $item = $(this);
                            let $card = $item.closest('.product-card');
                            let $radioInput = $item.find('input[type="radio"]');
                            let $group = $item.closest('.variant-group');

                            // Don't do anything if already selected
                            if ($item.hasClass('active')) return;

                            // Update selection within the same group only
                            $group.find('.variant-item').removeClass('active border-primary');
                            $item.addClass('active border-primary');
                            $group.find('input[type="radio"]').prop('checked', false);
                            $radioInput.prop('checked', true);

                            // Update variant display
                            updateVariantDisplay($card);

                            // Re-fetch price and then auto-update cart if already checked
                            fetchVariantPrice($card, function() {
                                if($card.find('.product-toggle').is(':checked')) {
                                    addToCart($card); // Re-add updates existing item in cart
                                } else {
                                    // Just update UI if not in cart
                                    updateProductUI($card);
                                    updateSummary();
                                }
                            });
                        });

                        /* 3. Quantity Controls */
                        $(document).on('click', '.quantity-plus, .quantity-minus', function(e) {
                            e.preventDefault();
                            let $card = $(this).closest('.product-card');
                            let $input = $card.find('.quantity-input');
                            let qty = parseInt($input.val());

                            if ($(this).hasClass('quantity-plus')) {
                                qty++;
                            } else if ($(this).hasClass('quantity-minus') && qty > 1) {
                                qty--;
                            }

                            $input.val(qty);

                            if($card.find('.product-toggle').is(':checked')) {
                                updateCartQuantity($card);
                            } else {
                                updateProductUI($card);
                                updateSummary();
                            }
                        });

                        /* 4. API Actions */
                        function addToCart($card) {
                            let productId = $card.find('.product-id').val();
                            let variantData = [];

                            $card.find('.attr_checkbox:checked').each(function() {
                                variantData.push($(this).val());
                            });

                            $.ajax({
                                url: "{{ route('landing.add-to-cart') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: productId,
                                    qty: $card.find('.quantity-input').val(),
                                    attribute_item_id: variantData
                                },
                                success: function(res) {
                                    if (res.success) {
                                        $card.find('.variant-sku').val(res.cart_id);
                                        updateProductUI($card);
                                        updateSummary();
                                    }
                                }
                            });
                        }

                        function removeFromCart($card) {
                            let sku = $card.find('.variant-sku').val();
                            $.ajax({
                                url: "{{ route('landing.remove-from-cart') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: sku
                                },
                                success: function(res) {
                                    if (res.success) {
                                        updateProductUI($card);
                                        updateSummary();
                                    }
                                }
                            });
                        }

                        function updateCartQuantity($card) {
                            let sku = $card.find('.variant-sku').val();
                            $.ajax({
                                url: "{{ route('landing.update-cart-quantity') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: sku,
                                    qty: $card.find('.quantity-input').val()
                                },
                                success: function(res) {
                                    if (res.success) {
                                        updateProductUI($card);
                                        updateSummary();
                                    }
                                }
                            });
                        }

                        function fetchVariantPrice($card, callback = null) {
                            let attrs = [];
                            $card.find('.variant-item input:checked').each(function() {
                                attrs.push($(this).val());
                            });

                            // If no variants are selected, use base price
                            if (attrs.length === 0) {
                                let basePrice = parseFloat($card.find('.base-price').val()) || 0;
                                $card.find('.variant-price').val(basePrice);
                                updateProductUI($card);
                                if(callback) callback();
                                updateSummary();
                                return;
                            }

                            $.ajax({
                                url: "{{ route('ajax.get.attributes') }}",
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    product_id: $card.find('.product-id').val(),
                                    attribute_item_id: attrs
                                },
                                success: function(res) {
                                    if (res.success == 200) {
                                        let price = parseFloat(res.data.sale_price) > 0 ? res.data.sale_price : res.data.regular_price;
                                        $card.find('.variant-price').val(price);

                                        // Update variant name display
                                        let names = '';
                                        if (res.data.variant_items && res.data.variant_items.length > 0) {
                                            names = res.data.variant_items.map(i => i.attribute_item).join(', ');
                                        }
                                        $card.find('.variant-name-value').val(names);

                                        // Update display for each variant group
                                        if (res.data.variant_items) {
                                            $card.find('.selected-variant-display').each(function(index) {
                                                if (res.data.variant_items[index]) {
                                                    $(this).text(res.data.variant_items[index].attribute_item);
                                                } else {
                                                    $(this).text('');
                                                }
                                            });
                                        }

                                        updateProductUI($card);
                                        if(callback) callback();
                                        updateSummary();
                                    }
                                },
                                error: function() {
                                    // Fallback to base price if API fails
                                    let basePrice = parseFloat($card.find('.base-price').val()) || 0;
                                    $card.find('.variant-price').val(basePrice);
                                    updateProductUI($card);
                                    if(callback) callback();
                                    updateSummary();
                                }
                            });
                        }

                        /* 5. Helper function to update variant display */
                        function updateVariantDisplay($card) {
                            let variantTexts = [];
                            $card.find('.variant-group').each(function() {
                                let $group = $(this);
                                let $selected = $group.find('input[type="radio"]:checked');
                                if ($selected.length) {
                                    let variantName = $selected.data('variant-name');
                                    if (variantName) {
                                        variantTexts.push(variantName);
                                    }
                                    // Update the display span for this group
                                    $group.find('.selected-variant-display').text(variantName || '');
                                }
                            });
                            $card.find('.variant-name-value').val(variantTexts.join(', '));
                        }

                        /* 6. Abandoned Cart Logic - 11 Digit Trigger */
                        $(document).on('input', '.customer_phone', function() {
                            let phone = $(this).val().trim();
                            let phonePattern = /^01[3-9]\d{8}$/;

                            if (phone.length === 11 && phonePattern.test(phone)) {
                                $.ajax({
                                    url: "{{ route('landing.abandoned-cart') }}",
                                    type: 'POST',
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        data: {
                                            name: $('.customer_name').val(),
                                            phone: $('.customer_phone').val(),
                                            address: $('.customer_address').val(),
                                            shipping_cost: 0
                                        }
                                    },
                                    success: function(res) {
                                        console.log('Abandoned cart saved/updated');
                                    }
                                });
                            }
                        });

                        /* 7. UI Rendering */
                        function updateProductUI($card) {
                            let qty = parseInt($card.find('.quantity-input').val()) || 1;
                            let base = parseFloat($card.find('.base-price').val()) || 0;
                            let variant = parseFloat($card.find('.variant-price').val()) || 0;
                            let price = variant > 0 ? variant : base;

                            $card.find('.line-price').text((qty * price).toFixed(2));
                        }

                        function updateSummary() {
                            let $tbody = $('#order-summary-body');
                            $tbody.empty();
                            let grandTotal = 0;

                            $('.product-card').each(function() {
                                let $card = $(this);
                                if ($card.find('.product-toggle').is(':checked')) {
                                    let linePrice = parseFloat($card.find('.line-price').text()) || 0;
                                    grandTotal += linePrice;

                                    let variantText = $card.find('.variant-name-value').val();
                                    let productName = $card.find('.product-name').text();
                                    let quantity = $card.find('.quantity-input').val();

                                    $tbody.append(`
                                        <tr>
                                            <td>
                                                <strong>${productName} × ${quantity}</strong><br>
                                                ${variantText ? `<small class="text-muted">${variantText}</small> ` : ''}
                                            </td>
                                            <td>${currency} ${linePrice.toFixed(2)}</td>
                                        </tr>
                                    `);
                                }
                            });

                            $('#summary-total').text(grandTotal.toFixed(2));
                            $('.order-place-button').html(`🛒 Place Order ${currency} ${grandTotal.toFixed(2)}`);
                            $('#submit_btn').prop('disabled', grandTotal <= 0);
                        }

                        // Initial price loading
                        $('.product-card').each(function() {
                            let $card = $(this);
                            if($card.find('.variant-group').length > 0) {
                                // First update the display
                                updateVariantDisplay($card);
                                // Then fetch the price
                                fetchVariantPrice($card);
                            } else {
                                updateProductUI($card);
                            }
                        });

                        // Initial summary update
                        setTimeout(updateSummary, 500);
                    });
                </script>


            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copy-right-text">
                <h2>Copyright © 2024 Pro Devs Ltd.</h2>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('landing-page/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('landing-page/assets/js/bootstrap.bundle.min.js') }}"></script>


    <script src="{{ asset('landing-page/assets/js/landing-page-edit.js') }}"></script>
    <script src="{{ asset('landing-page/assets/js/landing-page-3.js') }}"></script>



    @if (isset($enableEdit))
        <!-- Modals -->
        <div class="modal fade" id="image_modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="image_upload_form">
                            <div class="mb-3">
                                <label class="form-label">Select Image</label>
                                <input type="file" class="form-control" id="image_file" name="image" accept="image/*">
                            </div>
                            <input type="hidden" name="image_id" id="image_id">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="video_modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">YouTube Video URL</label>
                            <input type="text" class="form-control" id="video_url" placeholder="https://www.youtube.com/embed/...">
                        </div>
                        <input type="hidden" id="video_modal_id">
                        <button type="button" class="btn btn-primary video-upload-button">Update Video</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action_buttons">
            <a href="{{ route('admin.landing.pages.index') }}" class="landing_page_preview_button" title="Back">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back-up">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 14l-4 -4l4 -4" />
                    <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                </svg>
            </a>
            <button class="landing_page_save_button" type="button" title="Save">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" />
                </svg>
            </button>
            <a href="{{ route('landing-theme.home', $landingPage->slug) }}" target="_blank"
                class="landing_page_preview_button" title="Preview">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                </svg>
            </a>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('landing-page/assets/js/spectrum.min.js') }}"></script>
        <script src="{{ asset('landing-page/assets/js/toastr.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Toastr Configuration
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-right",
                    timeOut: 3000
                };

                // Editor Templates
                const TEXT_EDITOR = `
                    <span class="text_editor" contenteditable="false">
                        <ul>
                            <li class="fonts">
                                <span class="font_size">16</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18 9c.852 0 1.297 .986 .783 1.623l-.076 .084l-6 6a1 1 0 0 1 -1.32 .083l-.094 -.083l-6 -6l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057v-.118l.005 -.058l.009 -.06l.01 -.052l.032 -.108l.027 -.067l.07 -.132l.065 -.09l.073 -.081l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01l.057 -.004l12.059 -.002z" />
                                </svg>
                            </li>
                            <li class="font-bold" data-style="font-weight">B</li>
                            <li class="font-italic" data-style="font-style">I</li>
                            <li class="font-underline" data-style="text-decoration">U</li>
                            <li class="colors">
                                <span class="color_picker_label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-palette">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25" />
                                        <path d="M7.5 10.5a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M11.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15.5 10.5a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                    </svg>
                                </span>
                                <input type="text" class="color_picker" />
                            </li>
                            <li class="bg-color">
                                <span class="bg_color_picker_label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-contrast-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm0 2h-14a1 1 0 0 0 -1 1v14a1 1 0 0 0 .769 .973c3.499 -.347 7.082 -4.127 7.226 -7.747l.005 -.226c0 -3.687 3.66 -7.619 7.232 -7.974a1 1 0 0 0 -.232 -.026" />
                                    </svg>
                                </span>
                                <input type="text" class="bg_color_picker" />
                            </li>
                            <li>
                                <span class="close-button" title="Cancel">
                                    ✕
                                </span>
                            </li>
                        </ul>
                        <span class="editor_card">
                            <span class="font">
                                ${[12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,48,52,56,60].map(size =>
                                    `<a href="javascript:void(0);" data-size="${size}" class="font-item">${size}</a>`
                                ).join('')}
                            </span>
                        </span>
                    </span>`;

                // Close all editors
                function closeAllEditors() {
                    $('.lp_text_editable').removeClass('editing-active')
                        .css('border', '1px dashed #aaaaaab3')
                        .prop('contenteditable', false)
                        .find('.text_editor').remove();
                    $('.editor_card').removeClass('show');
                }

                // Initialize color pickers dynamically
                function initColorPickers(element) {
                    element.find('.color_picker').spectrum({
                        preferredFormat: "hex",
                        showInput: true,
                        showAlpha: true,
                        allowEmpty: false,
                        color: element.css('color') || '#000000',
                        change: function(color) {
                            $(this).closest('.lp_text_editable').css('color', color.toHexString());
                        }
                    });

                    element.find('.bg_color_picker').spectrum({
                        preferredFormat: "hex",
                        showInput: true,
                        showAlpha: true,
                        allowEmpty: false,
                        color: element.css('background-color') || '#ffffff',
                        change: function(color) {
                            $(this).closest('.lp_text_editable').css('background-color', color.toHexString());
                        }
                    });
                }

                // Text Editing
                $(document).on('dblclick', '.lp_text_editable', function(e) {
                    e.stopPropagation();
                    closeAllEditors();

                    const element = $(this);
                    element.addClass('editing-active')
                        .css('border', '2px solid #3b97e3')
                        .prop('contenteditable', true)
                        .append(TEXT_EDITOR);

                    // Get current styles
                    const fontSize = parseInt(element.css('font-size')) || 16;
                    const fontWeight = element.css('font-weight');
                    const fontStyle = element.css('font-style');
                    const textDecoration = element.css('text-decoration');

                    // Update UI
                    element.find('.font_size').text(fontSize);
                    element.find('.font-bold').toggleClass('active', fontWeight === 'bold' || parseInt(fontWeight) >= 700);
                    element.find('.font-italic').toggleClass('active', fontStyle === 'italic');
                    element.find('.font-underline').toggleClass('active', textDecoration.includes('underline'));

                    // Initialize color pickers
                    setTimeout(() => initColorPickers(element), 50);

                    // Focus
                    setTimeout(() => element.focus(), 100);
                });

                // Close editor
                $(document).on('click', '.close-button', function(e) {
                    e.stopPropagation();
                    closeAllEditors();
                });

                // Font size toggle
                $(document).on('click', '.fonts', function(e) {
                    e.stopPropagation();
                    $(this).closest('.text_editor').find('.editor_card').toggleClass('show');
                });

                // Apply font size
                $(document).on('click', '.font-item', function(e) {
                    e.stopPropagation();
                    const size = $(this).data('size');
                    const element = $(this).closest('.lp_text_editable');
                    element.css('font-size', size + 'px');
                    element.find('.font_size').text(size);
                    $('.editor_card').removeClass('show');
                });

                // Toggle text styles
                $(document).on('click', '.font-bold, .font-italic, .font-underline', function(e) {
                    e.stopPropagation();
                    const style = $(this).data('style');
                    const element = $(this).closest('.lp_text_editable');
                    const currentValue = element.css(style);

                    let newValue;
                    switch(style) {
                        case 'font-weight':
                            newValue = (currentValue === 'bold' || parseInt(currentValue) >= 700) ? 'normal' : 'bold';
                            break;
                        case 'font-style':
                            newValue = currentValue === 'italic' ? 'normal' : 'italic';
                            break;
                        case 'text-decoration':
                            newValue = currentValue.includes('underline') ? 'none' : 'underline';
                            break;
                        default:
                            newValue = currentValue;
                    }

                    element.css(style, newValue);
                    $(this).toggleClass('active', newValue !== 'normal' && newValue !== 'none');
                });

                // Open color pickers
                $(document).on('click', '.color_picker_label', function(e) {
                    e.stopPropagation();
                    $(this).closest('.colors').find('.color_picker').spectrum('show');
                });

                $(document).on('click', '.bg_color_picker_label', function(e) {
                    e.stopPropagation();
                    $(this).closest('.bg-color').find('.bg_color_picker').spectrum('show');
                });

                // Close on outside click
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.text_editor, .lp_text_editable.editing-active').length) {
                        $('.editor_card').removeClass('show');
                    }
                });

                // Close on Escape
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') closeAllEditors();
                });

                // ================ VIDEO FUNCTIONS ================
                function convertToEmbedUrl(url) {
                    const normal = url.match(/v=([^&]+)/);
                    const short  = url.match(/youtu\.be\/([^?]+)/);
                    const id = normal ? normal[1] : short ? short[1] : "";
                    return id ? "https://www.youtube.com/embed/" + id : "";
                }

                $(document).on('click', '.lp_video_editable', function () {
                    $('#video_modal_id').val($(this).data('video_id'));
                    $('#video_url').val($(this).find('iframe').attr('src') || "");
                    $('#video_modal').modal('show');
                });

                $(document).on('click', '.video-upload-button', function () {
                    const videoId = $('#video_modal_id').val();
                    const embedUrl = convertToEmbedUrl($('#video_url').val().trim());

                    if (!embedUrl) return toastr.error('Invalid YouTube URL');

                    $('.lp_video_editable[data-video_id="' + videoId + '"] iframe').attr('src', embedUrl);
                    $('#video_modal').modal('hide');
                    // toastr.success('Video updated!');
                });

                // ================ IMAGE FUNCTIONS ================
                $(document).on('click', '.lp_image_editable', function() {
                    const imageId = $(this).data('image_id');
                    $('#image_id').val(imageId);
                    $('#image_modal').modal('show');
                });

                $('#image_upload_form').on('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const button = $(this).find('button[type="submit"]');
                    const originalText = button.text();

                    button.html('<span class="spinner-border spinner-border-sm"></span> Uploading...')
                        .prop('disabled', true);

                    $.ajax({
                        url: "{{ route('admin.landing.page.upload.image') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                const imageId = $('#image_id').val();
                                $('.lp_image_editable[data-image_id="' + imageId + '"] img')
                                    .attr('src', response.path + '?t=' + Date.now());
                                $('#image_modal').modal('hide');
                                // toastr.success('Image uploaded!');
                            } else {
                                toastr.error(response.message || 'Upload failed');
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Upload error');
                        },
                        complete: function() {
                            button.text(originalText).prop('disabled', false);
                            $('#image_file').val('');
                        }
                    });
                });

                // ================ LIST MANAGEMENT ================
                $(document).on('click', '.card_list_append_button', function(e) {
                    e.preventDefault();
                    const type = $(this).data('type');
                    const list = $(this).closest('.card-list').find('ul');
                    const itemCount = list.find('li').length;
                    const listType = type == 1 ? 'subtext_one' : 'subtext_two';

                    const newItem = `
                        <li class="${type == 1 ? 'rule-item' : 'why-use-item'}">
                            <span>
                                ${type == 1 ?
                                    '<svg aria-hidden="true" class="e-font-icon-svg e-fas-check-circle" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>' :
                                    '<svg aria-hidden="true" class="e-font-icon-svg e-far-hand-point-right" viewBox="0 0 512 512"><path d="M428.8 137.6h-86.177a115.52 115.52 0 0 0 2.176-22.4c0-47.914-35.072-83.2-92-83.2-45.314 0-57.002 48.537-75.707 78.784-7.735 12.413-16.994 23.317-25.851 33.253l-.131.146-.129.148C135.662 161.807 127.764 168 120.8 168h-2.679c-5.747-4.952-13.536-8-22.12-8H32c-17.673 0-32 12.894-32 28.8v230.4C0 435.106 14.327 448 32 448h64c8.584 0 16.373-3.048 22.12-8h2.679c28.688 0 67.137 40 127.2 40h21.299c62.542 0 98.8-38.658 99.94-91.145 12.482-17.813 18.491-40.785 15.985-62.791A93.148 93.148 0 0 0 393.152 304H428.8c45.435 0 83.2-37.584 83.2-83.2 0-45.099-38.101-83.2-83.2-83.2zm0 118.4h-91.026c12.837 14.669 14.415 42.825-4.95 61.05 11.227 19.646 1.687 45.624-12.925 53.625 6.524 39.128-10.076 61.325-50.6 61.325H248c-45.491 0-77.21-35.913-120-39.676V215.571c25.239-2.964 42.966-21.222 59.075-39.596 11.275-12.65 21.725-25.3 30.799-39.875C232.355 112.712 244.006 80 252.8 80c23.375 0 44 8.8 44 35.2 0 35.2-26.4 53.075-26.4 70.4h158.4c18.425 0 35.2 16.5 35.2 35.2 0 18.975-16.225 35.2-35.2 35.2zM88 384c0 13.255-10.745 24-24 24s-24-10.745-24-24 10.745-24 24-24 24 10.745 24 24z"></path></svg>'
                                }
                            </span>
                            <span class="lp_text_editable ${listType} ${listType}_${itemCount}" style="">
                                New item ${itemCount + 1}
                            </span>
                            <button class="card_list_delete_button" data-type="${type}" data-key="${itemCount}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6l-12 12" />
                                    <path d="M6 6l12 12" />
                                </svg>
                            </button>
                        </li>
                    `;

                    $(this).before(newItem);
                    // toastr.success('Item added!');
                });

                $(document).on('click', '.card_list_delete_button', function(e) {
                    e.stopPropagation();
                    if (confirm('Delete this item?')) {
                        $(this).closest('li').remove();
                        // toastr.success('Item deleted!');
                    }
                });

                // ================ SECTION TOGGLES ================
                $(document).on('change', '.section_switch', function() {
                    const section = $(this).data('section');
                    const isChecked = $(this).is(':checked');
                    const container = $(this).closest('section').find('.container');
                    container.toggleClass('section_off', !isChecked);
                    // toastr.success(`Section ${section} ${isChecked ? 'enabled' : 'disabled'}!`);
                });

                // ================ SAVE FUNCTION ================
                $(document).on('click', '.landing_page_save_button', function() {
                    closeAllEditors();

                    const button = $(this);
                    const originalHtml = button.html();
                    button.html('<div class="spinner-border spinner-border-sm text-white"></div>')
                        .prop('disabled', true);

                    // Collect data
                    const data = { content: { main_text: [], subtext_one: [], subtext_two: [], videos: {}, images: {}, section_status: {} }, style: {} };

                    // Main text
                    for (let i = 0; i <= 10; i++) {
                        const el = $(`.get_main_text_${i}`);
                        if (!el.length) continue;

                        data.content.main_text[i] = el.text().trim();
                        let style = (el.attr('style') || '').replace(/border:\s*1px\s*dashed\s*rgba\(170,\s*170,\s*170,\s*0\.7\);?/i, '').trim();
                        data.style[`get_style_${i}`] = style;
                    }

                    // Subtexts
                    $('.subtext_one').each(function(i) {
                        data.content.subtext_one[i] = $(this).clone().find('.card_list_delete_button').remove().end().text().trim();
                    });

                    $('.subtext_two').each(function(i) {
                        data.content.subtext_two[i] = $(this).clone().find('.card_list_delete_button').remove().end().text().trim();
                    });

                    // Media
                    $('.lp_video_editable').each(function() {
                        const id = $(this).data('video_id');
                        const src = $(this).find('iframe').attr('src');
                        if (src) data.content.videos[id] = src;
                    });

                    $('.lp_image_editable').each(function() {
                        const id = $(this).data('image_id');
                        const src = $(this).find('img').attr('src');
                        if (src) data.content.images[id] = src.split('?')[0];
                    });

                    // Section status
                    $('.section_switch').each(function() {
                        const section = $(this).data('section');
                        data.content.section_status[`section_${section}`] = $(this).is(':checked') ? 'section_on' : 'section_off';
                    });

                    // Save
                    $.ajax({
                        url: "{{ route('admin.landing.page.save', $landingPage->id ?? 0) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            content: data.content,
                            style: data.style,
                            section_status: data.content.section_status
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Saved successfully!');
                            } else {
                                toastr.error(response.message || 'Save failed');
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Save error');
                        },
                        complete: function() {
                            button.html(originalHtml).prop('disabled', false);
                        }
                    });
                });

                // CSRF setup
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
            });
        </script>
    @endif

</body>
</html>
