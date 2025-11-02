<x-frontend-app-layout :title="'Venue Event Create'">
    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="create.html">Create</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Online Event</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="text-center main-title">
                        <h3>Create Venue Event</h3>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-9 col-md-12">
                    <div class="wizard-steps-block">
                        <div id="add-event-tab" class="step-app">
                            <ul class="step-steps">
                                <li class="active">
                                    <a href="#tab_step1">
                                        <span class="number"></span>
                                        <span class="step-name">Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_step2">
                                        <span class="number"></span>
                                        <span class="step-name">Tickets</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_step3">
                                        <span class="number"></span>
                                        <span class="step-name">Setting</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="step-content">
                                <div class="step-tab-panel step-tab-info active" id="tab_step1">
                                    <div class="tab-from-content">
                                        <div class="main-card">
                                            <div class="bp-title">
                                                <h4><i class="fa-solid fa-circle-info step_icon me-3"></i>Details</h4>
                                            </div>
                                            <div class="p-4 bp-form main-form">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="form-group border_bottom pb_30">
                                                            <label class="form-label fs-16">Give your event a name.*</label>
                                                            <p class="mt-2 mb-3 d-block fs-14">See how your name appears on the event page and a list of all places where your event name will be used. <a href="#" class="a-link">Learn more</a></p>
                                                            <input class="form-control h_50" type="text" placeholder="Enter event name here" value="">
                                                        </div>
                                                        <div class="form-group border_bottom pt_30 pb_30">
                                                            <label class="form-label fs-16">Choose a category for your event.*</label>
                                                            <p class="mt-2 mb-3 d-block fs-14">Choosing relevant categories helps to improve the discoverability of your event. <a href="#" class="a-link">Learn more</a></p>
                                                            <div class="dropdown bootstrap-select show-tick"><select class="selectpicker" multiple="" data-selected-text-format="count &gt; 4" data-size="5" title="Select category" data-live-search="true">
                                                                    <option value="01">Arts</option>
                                                                    <option value="02">Business</option>
                                                                    <option value="03">Coaching and Consulting</option>
                                                                    <option value="04">Community and Culture</option>
                                                                    <option value="05">Entrepreneurship</option>
                                                                    <option value="06">Education and Training</option>
                                                                    <option value="07">Family and Friends</option>
                                                                    <option value="08">Fashion and Beauty</option>
                                                                    <option value="09">Film and Entertainment</option>
                                                                    <option value="10">Food and Drink</option>
                                                                    <option value="11">Government and Politics</option>
                                                                    <option value="12">Health and Wellbeing</option>
                                                                    <option value="13">Hobbies and Interest</option>
                                                                    <option value="14">Music and Theater</option>
                                                                    <option value="15">Religion and Spirituality</option>
                                                                    <option value="16">Science and Technology</option>
                                                                    <option value="17">Sports and Fitness</option>
                                                                    <option value="18">Travel and Outdoor</option>
                                                                    <option value="19">Visual Arts</option>
                                                                    <option value="20">Others</option>
                                                                </select><button type="button" tabindex="-1" class="btn dropdown-toggle bs-placeholder btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-5" aria-haspopup="listbox" aria-expanded="false" title="Select category">
                                                                    <div class="filter-option">
                                                                        <div class="filter-option-inner">
                                                                            <div class="filter-option-inner-inner">Select category</div>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                                <div class="dropdown-menu ">
                                                                    <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-5" aria-autocomplete="list"></div>
                                                                    <div class="inner show" role="listbox" id="bs-select-5" tabindex="-1" aria-multiselectable="true">
                                                                        <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group border_bottom pt_30 pb_30">
                                                            <label class="form-label fs-16">When is your event?*</label>
                                                            <p class="mt-2 mb-3 fs-14 d-block">Tell your attendees when your event starts so they can get ready to attend.</p>
                                                            <div class="row g-2">
                                                                <div class="col-md-6">
                                                                    <label class="mt-3 form-label fs-6">Event Date.*</label>
                                                                    <div class="loc-group position-relative">
                                                                        <input class="form-control h_50 datepicker-here" data-language="en" type="text" placeholder="MM/DD/YYYY" value="">
                                                                        <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row g-2">
                                                                        <div class="col-md-6">
                                                                            <div class="clock-icon">
                                                                                <label class="mt-3 form-label fs-6">Time</label>
                                                                                <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" data-live-search="true">
                                                                                        <option value="00:00">12:00 AM</option>
                                                                                        <option value="00:15">12:15 AM</option>
                                                                                        <option value="00:30">12:30 AM</option>
                                                                                        <option value="00:45">12:45 AM</option>
                                                                                        <option value="01:00">01:00 AM</option>
                                                                                        <option value="01:15">01:15 AM</option>
                                                                                        <option value="01:30">01:30 AM</option>
                                                                                        <option value="01:45">01:45 AM</option>
                                                                                        <option value="02:00">02:00 AM</option>
                                                                                        <option value="02:15">02:15 AM</option>
                                                                                        <option value="02:30">02:30 AM</option>
                                                                                        <option value="02:45">02:45 AM</option>
                                                                                        <option value="03:00">03:00 AM</option>
                                                                                        <option value="03:15">03:15 AM</option>
                                                                                        <option value="03:30">03:30 AM</option>
                                                                                        <option value="03:45">03:45 AM</option>
                                                                                        <option value="04:00">04:00 AM</option>
                                                                                        <option value="04:15">04:15 AM</option>
                                                                                        <option value="04:30">04:30 AM</option>
                                                                                        <option value="04:45">04:45 AM</option>
                                                                                        <option value="05:00">05:00 AM</option>
                                                                                        <option value="05:15">05:15 AM</option>
                                                                                        <option value="05:30">05:30 AM</option>
                                                                                        <option value="05:45">05:45 AM</option>
                                                                                        <option value="06:00">06:00 AM</option>
                                                                                        <option value="06:15">06:15 AM</option>
                                                                                        <option value="06:30">06:30 AM</option>
                                                                                        <option value="06:45">06:45 AM</option>
                                                                                        <option value="07:00">07:00 AM</option>
                                                                                        <option value="07:15">07:15 AM</option>
                                                                                        <option value="07:30">07:30 AM</option>
                                                                                        <option value="07:45">07:45 AM</option>
                                                                                        <option value="08:00">08:00 AM</option>
                                                                                        <option value="08:15">08:15 AM</option>
                                                                                        <option value="08:30">08:30 AM</option>
                                                                                        <option value="08:45">08:45 AM</option>
                                                                                        <option value="09:00">09:00 AM</option>
                                                                                        <option value="09:15">09:15 AM</option>
                                                                                        <option value="09:30">09:30 AM</option>
                                                                                        <option value="09:45">09:45 AM</option>
                                                                                        <option value="10:00" selected="selected">10:00 AM</option>
                                                                                        <option value="10:15">10:15 AM</option>
                                                                                        <option value="10:30">10:30 AM</option>
                                                                                        <option value="10:45">10:45 AM</option>
                                                                                        <option value="11:00">11:00 AM</option>
                                                                                        <option value="11:15">11:15 AM</option>
                                                                                        <option value="11:30">11:30 AM</option>
                                                                                        <option value="11:45">11:45 AM</option>
                                                                                        <option value="12:00">12:00 PM</option>
                                                                                        <option value="12:15">12:15 PM</option>
                                                                                        <option value="12:30">12:30 PM</option>
                                                                                        <option value="12:45">12:45 PM</option>
                                                                                        <option value="13:00">01:00 PM</option>
                                                                                        <option value="13:15">01:15 PM</option>
                                                                                        <option value="13:30">01:30 PM</option>
                                                                                        <option value="13:45">01:45 PM</option>
                                                                                        <option value="14:00">02:00 PM</option>
                                                                                        <option value="14:15">02:15 PM</option>
                                                                                        <option value="14:30">02:30 PM</option>
                                                                                        <option value="14:45">02:45 PM</option>
                                                                                        <option value="15:00">03:00 PM</option>
                                                                                        <option value="15:15">03:15 PM</option>
                                                                                        <option value="15:30">03:30 PM</option>
                                                                                        <option value="15:45">03:45 PM</option>
                                                                                        <option value="16:00">04:00 PM</option>
                                                                                        <option value="16:15">04:15 PM</option>
                                                                                        <option value="16:30">04:30 PM</option>
                                                                                        <option value="16:45">04:45 PM</option>
                                                                                        <option value="17:00">05:00 PM</option>
                                                                                        <option value="17:15">05:15 PM</option>
                                                                                        <option value="17:30">05:30 PM</option>
                                                                                        <option value="17:45">05:45 PM</option>
                                                                                        <option value="18:00">06:00 PM</option>
                                                                                        <option value="18:15">06:15 PM</option>
                                                                                        <option value="18:30">06:30 PM</option>
                                                                                        <option value="18:45">06:45 PM</option>
                                                                                        <option value="19:00">07:00 PM</option>
                                                                                        <option value="19:15">07:15 PM</option>
                                                                                        <option value="19:30">07:30 PM</option>
                                                                                        <option value="19:45">07:45 PM</option>
                                                                                        <option value="20:00">08:00 PM</option>
                                                                                        <option value="20:15">08:15 PM</option>
                                                                                        <option value="20:30">08:30 PM</option>
                                                                                        <option value="20:45">08:45 PM</option>
                                                                                        <option value="21:00">09:00 PM</option>
                                                                                        <option value="21:15">09:15 PM</option>
                                                                                        <option value="21:30">09:30 PM</option>
                                                                                        <option value="21:45">09:45 PM</option>
                                                                                        <option value="22:00">10:00 PM</option>
                                                                                        <option value="22:15">10:15 PM</option>
                                                                                        <option value="22:30">10:30 PM</option>
                                                                                        <option value="22:45">10:45 PM</option>
                                                                                        <option value="23:00">11:00 PM</option>
                                                                                        <option value="23:15">11:15 PM</option>
                                                                                        <option value="23:30">11:30 PM</option>
                                                                                        <option value="23:45">11:45 PM</option>
                                                                                    </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-6" aria-haspopup="listbox" aria-expanded="false" title="10:00 AM">
                                                                                        <div class="filter-option">
                                                                                            <div class="filter-option-inner">
                                                                                                <div class="filter-option-inner-inner">10:00 AM</div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </button>
                                                                                    <div class="dropdown-menu ">
                                                                                        <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-6" aria-autocomplete="list"></div>
                                                                                        <div class="inner show" role="listbox" id="bs-select-6" tabindex="-1">
                                                                                            <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="mt-3 form-label fs-6">Duration</label>
                                                                            <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" data-live-search="true">
                                                                                    <option value="15">15m</option>
                                                                                    <option value="30">30m</option>
                                                                                    <option value="45">45m</option>
                                                                                    <option value="60" selected="selected">1h</option>
                                                                                    <option value="75">1h 15m</option>
                                                                                    <option value="90">1h 30m</option>
                                                                                    <option value="105">1h 45m</option>
                                                                                    <option value="120">2h</option>
                                                                                    <option value="135">2h 15m</option>
                                                                                    <option value="150">2h 30m</option>
                                                                                    <option value="165">2h 45m</option>
                                                                                    <option value="180">3h</option>
                                                                                    <option value="195">3h 15m</option>
                                                                                    <option value="210">3h 30m</option>
                                                                                    <option value="225">3h 45m</option>
                                                                                </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-7" aria-haspopup="listbox" aria-expanded="false" title="1h">
                                                                                    <div class="filter-option">
                                                                                        <div class="filter-option-inner">
                                                                                            <div class="filter-option-inner-inner">1h</div>
                                                                                        </div>
                                                                                    </div>
                                                                                </button>
                                                                                <div class="dropdown-menu ">
                                                                                    <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-7" aria-autocomplete="list"></div>
                                                                                    <div class="inner show" role="listbox" id="bs-select-7" tabindex="-1">
                                                                                        <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group pt_30 pb_30">
                                                            <label class="form-label fs-16">Add a few images to your event banner.</label>
                                                            <p class="mt-2 mb-3 fs-14 d-block pe_right">Upload colorful and vibrant images as the banner for your event! See how beautiful images help your event details page. <a href="#" class="a-link">Learn more</a></p>
                                                            <div class="mt-4 content-holder">
                                                                <div class="default-event-thumb">
                                                                    <div class="default-event-thumb-btn">
                                                                        <div class="thumb-change-btn">
                                                                            <input type="file" id="thumb-img">
                                                                            <label for="thumb-img">Change Image</label>
                                                                        </div>
                                                                    </div>
                                                                    <img src="images/banners/custom-img.jpg" alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group border_bottom pb_30">
                                                            <label class="form-label fs-16">Please describe your event.</label>
                                                            <p class="mt-2 mb-3 fs-14 d-block">Write a few words below to describe your event and provide any extra information such as schedules, itinerary or any special instructions required to attend your event.</p>
                                                            <div class="mt-4 text-editor">
                                                                <div id="pd_editor" style="display: none;"></div>
                                                                <div class="ck ck-reset ck-editor ck-rounded-corners" role="application" dir="ltr" lang="en" aria-labelledby="ck-editor__label_e926446ed519c90173e6cd3e530c749c4"><label class="ck ck-label ck-voice-label" id="ck-editor__label_e926446ed519c90173e6cd3e530c749c4">Rich Text Editor</label>
                                                                    <div class="ck ck-editor__top ck-reset_all" role="presentation">
                                                                        <div class="ck ck-sticky-panel">
                                                                            <div class="ck ck-sticky-panel__placeholder" style="display: none;"></div>
                                                                            <div class="ck ck-sticky-panel__content">
                                                                                <div class="ck ck-toolbar ck-toolbar_grouping" role="toolbar" aria-label="Editor toolbar">
                                                                                    <div class="ck ck-toolbar__items">
                                                                                        <div class="ck ck-dropdown ck-heading-dropdown"><button class="ck ck-button ck-off ck-button_with-text ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e420620b9fb8bb8653f1270c3a69a83f5" aria-haspopup="true"><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Heading</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e420620b9fb8bb8653f1270c3a69a83f5">Paragraph</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                                                                    <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                                                                                </svg></button>
                                                                                            <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se">
                                                                                                <ul class="ck ck-reset ck-list">
                                                                                                    <li class="ck ck-list__item"><button class="ck ck-button ck-heading_paragraph ck-on ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eb493f817b30fc4171d64f30e3f447709"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eb493f817b30fc4171d64f30e3f447709">Paragraph</span></button></li>
                                                                                                    <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading1 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e9ba632364949fc17a36134f00fb90860"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e9ba632364949fc17a36134f00fb90860">Heading 1</span></button></li>
                                                                                                    <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading2 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e2372af5919c5f4d08324a1c8e1469b68"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e2372af5919c5f4d08324a1c8e1469b68">Heading 2</span></button></li>
                                                                                                    <li class="ck ck-list__item"><button class="ck ck-button ck-heading_heading3 ck-off ck-button_with-text" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ef14091b171e674e262c2f862294a6a94"><span class="ck ck-tooltip ck-tooltip_s ck-hidden"><span class="ck ck-tooltip__text"></span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ef14091b171e674e262c2f862294a6a94">Heading 3</span></button></li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div><span class="ck ck-toolbar__separator"></span><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e7728f8fedc31f5297d2ecee9e0ff2130" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M10.187 17H5.773c-.637 0-1.092-.138-1.364-.415-.273-.277-.409-.718-.409-1.323V4.738c0-.617.14-1.062.419-1.332.279-.27.73-.406 1.354-.406h4.68c.69 0 1.288.041 1.793.124.506.083.96.242 1.36.478.341.197.644.447.906.75a3.262 3.262 0 0 1 .808 2.162c0 1.401-.722 2.426-2.167 3.075C15.05 10.175 16 11.315 16 13.01a3.756 3.756 0 0 1-2.296 3.504 6.1 6.1 0 0 1-1.517.377c-.571.073-1.238.11-2 .11zm-.217-6.217H7v4.087h3.069c1.977 0 2.965-.69 2.965-2.072 0-.707-.256-1.22-.768-1.537-.512-.319-1.277-.478-2.296-.478zM7 5.13v3.619h2.606c.729 0 1.292-.067 1.69-.2a1.6 1.6 0 0 0 .91-.765c.165-.267.247-.566.247-.897 0-.707-.26-1.176-.778-1.409-.519-.232-1.31-.348-2.375-.348H7z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Bold (CTRL+B)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e7728f8fedc31f5297d2ecee9e0ff2130">Bold</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e944d1b0d775fa6b18667d07feebd2f39" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M9.586 14.633l.021.004c-.036.335.095.655.393.962.082.083.173.15.274.201h1.474a.6.6 0 1 1 0 1.2H5.304a.6.6 0 0 1 0-1.2h1.15c.474-.07.809-.182 1.005-.334.157-.122.291-.32.404-.597l2.416-9.55a1.053 1.053 0 0 0-.281-.823 1.12 1.12 0 0 0-.442-.296H8.15a.6.6 0 0 1 0-1.2h6.443a.6.6 0 1 1 0 1.2h-1.195c-.376.056-.65.155-.823.296-.215.175-.423.439-.623.79l-2.366 9.347z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Italic (CTRL+I)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e944d1b0d775fa6b18667d07feebd2f39">Italic</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ed23023a350f34c33409f5f30a44a0a51" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M11.077 15l.991-1.416a.75.75 0 1 1 1.229.86l-1.148 1.64a.748.748 0 0 1-.217.206 5.251 5.251 0 0 1-8.503-5.955.741.741 0 0 1 .12-.274l1.147-1.639a.75.75 0 1 1 1.228.86L4.933 10.7l.006.003a3.75 3.75 0 0 0 6.132 4.294l.006.004zm5.494-5.335a.748.748 0 0 1-.12.274l-1.147 1.639a.75.75 0 1 1-1.228-.86l.86-1.23a3.75 3.75 0 0 0-6.144-4.301l-.86 1.229a.75.75 0 0 1-1.229-.86l1.148-1.64a.748.748 0 0 1 .217-.206 5.251 5.251 0 0 1 8.503 5.955zm-4.563-2.532a.75.75 0 0 1 .184 1.045l-3.155 4.505a.75.75 0 1 1-1.229-.86l3.155-4.506a.75.75 0 0 1 1.045-.184z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Link (Ctrl+K)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ed23023a350f34c33409f5f30a44a0a51">Link</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e86043325babc75b7ba3c77cf6c6b38ce" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M7 5.75c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zm-6 0C1 4.784 1.777 4 2.75 4c.966 0 1.75.777 1.75 1.75 0 .966-.777 1.75-1.75 1.75C1.784 7.5 1 6.723 1 5.75zm6 9c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zm-6 0c0-.966.777-1.75 1.75-1.75.966 0 1.75.777 1.75 1.75 0 .966-.777 1.75-1.75 1.75-.966 0-1.75-.777-1.75-1.75z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Bulleted List</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e86043325babc75b7ba3c77cf6c6b38ce">Bulleted List</span></button><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e091bce0d78e3b1eb3b469afb15d2f21a" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M7 5.75c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM3.5 3v5H2V3.7H1v-1h2.5V3zM.343 17.857l2.59-3.257H2.92a.6.6 0 1 0-1.04 0H.302a2 2 0 1 1 3.995 0h-.001c-.048.405-.16.734-.333.988-.175.254-.59.692-1.244 1.312H4.3v1h-4l.043-.043zM7 14.75a.75.75 0 0 1 .75-.75h9.5a.75.75 0 1 1 0 1.5h-9.5a.75.75 0 0 1-.75-.75z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Numbered List</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e091bce0d78e3b1eb3b469afb15d2f21a">Numbered List</span></button><span class="ck ck-toolbar__separator"></span><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ef0600c13ffb47b015192ce3ae0608c4b" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M2 3.75c0 .414.336.75.75.75h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 0 0-.75.75zm5 6c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM2.75 16.5h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 1 0 0 1.5zM1.632 6.95L5.02 9.358a.4.4 0 0 1-.013.661l-3.39 2.207A.4.4 0 0 1 1 11.892V7.275a.4.4 0 0 1 .632-.326z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Increase indent</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ef0600c13ffb47b015192ce3ae0608c4b">Increase indent</span></button><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e8e6cf7a2e300936158c266ef41ee5dae" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M2 3.75c0 .414.336.75.75.75h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 0 0-.75.75zm5 6c0 .414.336.75.75.75h9.5a.75.75 0 1 0 0-1.5h-9.5a.75.75 0 0 0-.75.75zM2.75 16.5h14.5a.75.75 0 1 0 0-1.5H2.75a.75.75 0 1 0 0 1.5zm1.618-9.55L.98 9.358a.4.4 0 0 0 .013.661l3.39 2.207A.4.4 0 0 0 5 11.892V7.275a.4.4 0 0 0-.632-.326z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Decrease indent</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e8e6cf7a2e300936158c266ef41ee5dae">Decrease indent</span></button><span class="ck ck-toolbar__separator"></span><span class="ck-file-dialog-button"><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_eae28e048572bf4c81aef040d5d67006b"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                    <path d="M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.1-1.05h14.38zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z"></path>
                                                                                                </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert image</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eae28e048572bf4c81aef040d5d67006b">Insert image</span></button><input class="ck-hidden" type="file" tabindex="-1" accept="image/jpeg,image/png,image/gif,image/bmp,image/webp,image/tiff" multiple="true"></span><button class="ck ck-button ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e7adcd798d2e76fccea99daa18d7e492b" aria-pressed="false"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M3 10.423a6.5 6.5 0 0 1 6.056-6.408l.038.67C6.448 5.423 5.354 7.663 5.22 10H9c.552 0 .5.432.5.986v4.511c0 .554-.448.503-1 .503h-5c-.552 0-.5-.449-.5-1.003v-4.574zm8 0a6.5 6.5 0 0 1 6.056-6.408l.038.67c-2.646.739-3.74 2.979-3.873 5.315H17c.552 0 .5.432.5.986v4.511c0 .554-.448.503-1 .503h-5c-.552 0-.5-.449-.5-1.003v-4.574z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Block quote</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e7adcd798d2e76fccea99daa18d7e492b">Block quote</span></button>
                                                                                        <div class="ck ck-dropdown"><button class="ck ck-button ck-off ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ee5d308f8b5be3f1e39948bfac844d6d7" aria-haspopup="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                    <path d="M3 6v3h4V6H3zm0 4v3h4v-3H3zm0 4v3h4v-3H3zm5 3h4v-3H8v3zm5 0h4v-3h-4v3zm4-4v-3h-4v3h4zm0-4V6h-4v3h4zm1.5 8a1.5 1.5 0 0 1-1.5 1.5H3A1.5 1.5 0 0 1 1.5 17V4c.222-.863 1.068-1.5 2-1.5h13c.932 0 1.778.637 2 1.5v13zM12 13v-3H8v3h4zm0-4V6H8v3h4z"></path>
                                                                                                </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert table</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ee5d308f8b5be3f1e39948bfac844d6d7">Insert table</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                                                                    <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                                                                                </svg></button>
                                                                                            <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se"></div>
                                                                                        </div>
                                                                                        <div class="ck ck-dropdown"><button class="ck ck-button ck-off ck-dropdown__button" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_ed3f187e43c98fd3106bb4856cf744700" aria-haspopup="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                    <path d="M18.68 3.03c.6 0 .59-.03.59.55v12.84c0 .59.01.56-.59.56H1.29c-.6 0-.59.03-.59-.56V3.58c0-.58-.01-.55.6-.55h17.38zM15.77 15V5H4.2v10h11.57zM2 4v1h1V4H2zm0 2v1h1V6H2zm0 2v1h1V8H2zm0 2v1h1v-1H2zm0 2v1h1v-1H2zm0 2v1h1v-1H2zM17 4v1h1V4h-1zm0 2v1h1V6h-1zm0 2v1h1V8h-1zm0 2v1h1v-1h-1zm0 2v1h1v-1h-1zm0 2v1h1v-1h-1zM7.5 7.177a.4.4 0 0 1 .593-.351l5.133 2.824a.4.4 0 0 1 0 .7l-5.133 2.824a.4.4 0 0 1-.593-.35V7.176v.001z"></path>
                                                                                                </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Insert media</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_ed3f187e43c98fd3106bb4856cf744700">Insert media</span><svg class="ck ck-icon ck-dropdown__arrow" viewBox="0 0 10 10">
                                                                                                    <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.168-.136L.941 4.523z"></path>
                                                                                                </svg></button>
                                                                                            <div class="ck ck-reset ck-dropdown__panel ck-dropdown__panel_se">
                                                                                                <form class="ck ck-media-form" tabindex="-1">
                                                                                                    <div class="ck ck-labeled-field-view"><label class="ck ck-label" id="ck-editor__label_e417ea02e831529fdb247ec0ad913071b" for="ck-labeled-field-view-e6e78e370daea16c55142079d8021a11f">Media URL</label><input type="text" class="ck ck-input ck-input-text" id="ck-labeled-field-view-e6e78e370daea16c55142079d8021a11f" placeholder="https://example.com" aria-describedby="ck-labeled-field-view-status-e0d24384938f0be4a6a8583b6f1ed9104">
                                                                                                        <div class="ck ck-labeled-field-view__status" id="ck-labeled-field-view-status-e0d24384938f0be4a6a8583b6f1ed9104">Paste the media URL in the input.</div>
                                                                                                    </div><button class="ck ck-button ck-off ck-button-save" type="submit" tabindex="-1" aria-labelledby="ck-editor__aria-label_eb29debbda3b36354bfcf01365af139cf"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                            <path d="M6.972 16.615a.997.997 0 0 1-.744-.292l-4.596-4.596a1 1 0 1 1 1.414-1.414l3.926 3.926 9.937-9.937a1 1 0 0 1 1.414 1.415L7.717 16.323a.997.997 0 0 1-.745.292z"></path>
                                                                                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Save</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_eb29debbda3b36354bfcf01365af139cf">Save</span></button><button class="ck ck-button ck-off ck-button-cancel" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e708d2b06aa9904b98f9493f8cdbf19a7"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                            <path d="M11.591 10.177l4.243 4.242a1 1 0 0 1-1.415 1.415l-4.242-4.243-4.243 4.243a1 1 0 0 1-1.414-1.415l4.243-4.242L4.52 5.934A1 1 0 0 1 5.934 4.52l4.243 4.243 4.242-4.243a1 1 0 1 1 1.415 1.414l-4.243 4.243z"></path>
                                                                                                        </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Cancel</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e708d2b06aa9904b98f9493f8cdbf19a7">Cancel</span></button>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e77b27d70cf8ac52b463ccd78f1ddd0ab" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M5.042 9.367l2.189 1.837a.75.75 0 0 1-.965 1.149l-3.788-3.18a.747.747 0 0 1-.21-.284.75.75 0 0 1 .17-.945L6.23 4.762a.75.75 0 1 1 .964 1.15L4.863 7.866h8.917A.75.75 0 0 1 14 7.9a4 4 0 1 1-1.477 7.718l.344-1.489a2.5 2.5 0 1 0 1.094-4.73l.008-.032H5.042z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Undo (CTRL+Z)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e77b27d70cf8ac52b463ccd78f1ddd0ab">Undo</span></button><button class="ck ck-button ck-disabled ck-off" type="button" tabindex="-1" aria-labelledby="ck-editor__aria-label_e54e32f197548b28811936d19d1c43df9" aria-disabled="true"><svg class="ck ck-icon ck-button__icon" viewBox="0 0 20 20">
                                                                                                <path d="M14.958 9.367l-2.189 1.837a.75.75 0 0 0 .965 1.149l3.788-3.18a.747.747 0 0 0 .21-.284.75.75 0 0 0-.17-.945L13.77 4.762a.75.75 0 1 0-.964 1.15l2.331 1.955H6.22A.75.75 0 0 0 6 7.9a4 4 0 1 0 1.477 7.718l-.344-1.489A2.5 2.5 0 1 1 6.039 9.4l-.008-.032h8.927z"></path>
                                                                                            </svg><span class="ck ck-tooltip ck-tooltip_s"><span class="ck ck-tooltip__text">Redo (CTRL+Y)</span></span><span class="ck ck-button__label" id="ck-editor__aria-label_e54e32f197548b28811936d19d1c43df9">Redo</span></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ck ck-editor__main" role="presentation">
                                                                        <div class="ck-blurred ck ck-content ck-editor__editable ck-rounded-corners ck-editor__editable_inline" lang="en" dir="ltr" role="textbox" aria-label="Rich Text Editor, main" contenteditable="true">
                                                                            <p><br data-cke-filler="true"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pb-2 form-group pt_30">
                                                            <label class="form-label fs-16">Where is your event taking place? *</label>
                                                            <p class="mt-2 mb-3 fs-14 d-block">Add a venue to your event to tell your attendees where to join the event.</p>
                                                            <div class="stepper-data-set">
                                                                <div class="content-holder template-selector">
                                                                    <div class="row g-4">
                                                                        <div class="col-md-12">
                                                                            <div class="venue-event">
                                                                                <div class="map">
                                                                                    <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d27382.59422947023!2d75.84077125074462!3d30.919535510612153!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1534312417365" style="border:0" allowfullscreen=""></iframe>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label fs-6">Venue*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label fs-6">Address line 1*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label fs-6">Address line 2*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-1 form-group main-form">
                                                                                <label class="form-label">Country*</label>
                                                                                <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" title="Nothing selected" data-live-search="true">
                                                                                        <option class="bs-title-option" value=""></option>
                                                                                        <option value="Algeria">Algeria</option>
                                                                                        <option value="Argentina">Argentina</option>
                                                                                        <option value="Australia" selected="">Australia</option>
                                                                                        <option value="Austria">Austria (Österreich)</option>
                                                                                        <option value="Belgium">Belgium (België)</option>
                                                                                        <option value="Bolivia">Bolivia</option>
                                                                                        <option value="Brazil">Brazil</option>
                                                                                        <option value="Canada">Canada</option>
                                                                                        <option value="Chile">Chile</option>
                                                                                        <option value="Colombia">Colombia</option>
                                                                                        <option value="Costa Rica">Costa Rica</option>
                                                                                        <option value="Cyprus">Cyprus</option>
                                                                                        <option value="Czech Republic">Czech Republic</option>
                                                                                        <option value="Denmark">Denmark</option>
                                                                                        <option value="Dominican Republic">Dominican Republic</option>
                                                                                        <option value="Estonia">Estonia</option>
                                                                                        <option value="Finland">Finland</option>
                                                                                        <option value="France">France</option>
                                                                                        <option value="Germany">Germany</option>
                                                                                        <option value="Greece">Greece</option>
                                                                                        <option value="Hong Kong">Hong Kong</option>
                                                                                        <option value="Iceland">Iceland</option>
                                                                                        <option value="India">India</option>
                                                                                        <option value="Indonesia">Indonesia</option>
                                                                                        <option value="Ireland">Ireland</option>
                                                                                        <option value="Israel">Israel</option>
                                                                                        <option value="Italy">Italy</option>
                                                                                        <option value="Japan">Japan</option>
                                                                                        <option value="Latvia">Latvia</option>
                                                                                        <option value="Lithuania">Lithuania</option>
                                                                                        <option value="Luxembourg">Luxembourg</option>
                                                                                        <option value="Malaysia">Malaysia</option>
                                                                                        <option value="Mexico">Mexico</option>
                                                                                        <option value="Nepal">Nepal</option>
                                                                                        <option value="Netherlands">Netherlands</option>
                                                                                        <option value="New Zealand">New Zealand</option>
                                                                                        <option value="Norway">Norway</option>
                                                                                        <option value="Paraguay">Paraguay</option>
                                                                                        <option value="Peru">Peru</option>
                                                                                        <option value="Philippines">Philippines</option>
                                                                                        <option value="Poland">Poland</option>
                                                                                        <option value="Portugal">Portugal</option>
                                                                                        <option value="Singapore">Singapore</option>
                                                                                        <option value="Slovakia">Slovakia</option>
                                                                                        <option value="Slovenia">Slovenia</option>
                                                                                        <option value="South Africa">South Africa</option>
                                                                                        <option value="South Korea">South Korea</option>
                                                                                        <option value="Spain">Spain</option>
                                                                                        <option value="Sweden">Sweden</option>
                                                                                        <option value="Switzerland">Switzerland</option>
                                                                                        <option value="Tanzania">Tanzania</option>
                                                                                        <option value="Thailand">Thailand</option>
                                                                                        <option value="Turkey">Turkey</option>
                                                                                        <option value="United Kingdom">United Kingdom</option>
                                                                                        <option value="United States">United States</option>
                                                                                        <option value="Vietnam">Vietnam</option>
                                                                                    </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-8" aria-haspopup="listbox" aria-expanded="false" title="Australia">
                                                                                        <div class="filter-option">
                                                                                            <div class="filter-option-inner">
                                                                                                <div class="filter-option-inner-inner">Australia</div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </button>
                                                                                    <div class="dropdown-menu ">
                                                                                        <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-8" aria-autocomplete="list"></div>
                                                                                        <div class="inner show" role="listbox" id="bs-select-8" tabindex="-1">
                                                                                            <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label">State*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="Victoria">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-12">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label">City/Suburb*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="Melbourne">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-12">
                                                                            <div class="mt-1 form-group">
                                                                                <label class="form-label">Zip/Post Code*</label>
                                                                                <input class="form-control h_50" type="text" placeholder="" value="3000">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-tab-panel step-tab-gallery" id="tab_step2">
                                    <div class="tab-from-content">
                                        <div class="main-card">
                                            <div class="bp-title">
                                                <h4><i class="fa-solid fa-ticket step_icon me-3"></i>Tickets</h4>
                                            </div>
                                            <div class="bp-form main-form">
                                                <div class="p-4 form-group border_bottom pb_30">
                                                    <div class="">
                                                        <div class="ticket-section">
                                                            <label class="form-label fs-16">Let's create tickets!</label>
                                                            <p class="mt-2 mb-3 fs-14 d-block pe_right">Create tickets for your event by clicking on the 'Add Tickets' button below.</p>
                                                        </div>
                                                        <div class="pt-4 pb-3 d-flex align-items-center justify-content-between full-width">
                                                            <h3 class="mb-0 fs-18">Tickets (<span class="venue-event-ticket-counter">3</span>)</h3>
                                                            <div class="dropdown dropdown-default dropdown-normal btn-ticket-type-top">
                                                                <button class="dropdown-toggle main-btn btn-hover h_40 pe-4 ps-4" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span>Add Tickets</span>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#singleTicketModal">
                                                                        <i class="fa-solid fa-ticket me-2"></i>
                                                                        Single Ticket
                                                                    </a>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#groupTicketModal">
                                                                        <i class="fa-solid fa-ticket me-2"></i>
                                                                        Group Ticket
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-center ticket-type-item-empty d-none p_30">
                                                            <div class="ticket-list-icon d-inline-block">
                                                                <img src="images/ticket.png" alt="">
                                                            </div>
                                                            <h4 class="mt-4 mb-3 color-black fs-18">You have no tickets yet.</h4>
                                                            <p class="mb-0">You have not created a ticket yet. Please click the button above to create your event ticket.</p>
                                                        </div>
                                                        <div class="mt-4 ticket-type-item-list">
                                                            <div class="mt-4 price-ticket-card">
                                                                <div class="flex-wrap p-4 price-ticket-card-head d-md-flex align-items-start justify-content-between position-relative">
                                                                    <div class="d-flex align-items-center top-name">
                                                                        <div class="icon-box">
                                                                            <span class="icon-big rotate-icon icon icon-purple">
                                                                                <i class="fa-solid fa-ticket"></i>
                                                                            </span>
                                                                            <h5 class="mt-1 mb-1 fs-16">New Small - $10.00</h5>
                                                                            <p class="m-0 text-gray-50"><span class="visitor-date-time">May 3, 2022</span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="price-badge">
                                                                            <img src="images/discount.png" alt="">
                                                                        </div>
                                                                        <label class="mt-1 mb-0 btn-switch tfs-8 me-4">
                                                                            <input type="checkbox" value="" checked="">
                                                                            <span class="checkbox-slider"></span>
                                                                        </label>
                                                                        <div class="dropdown dropdown-default dropdown-text dropdown-icon-item">
                                                                            <button class="option-btn-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                <a href="#" class="dropdown-item"><i class="fa-solid fa-pen me-3"></i>Edit</a>
                                                                                <a href="#" class="dropdown-item"><i class="fa-solid fa-trash-can me-3"></i>Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="p-4 price-ticket-card-body border_top">
                                                                    <div class="flex-wrap full-width d-flex justify-content-between align-items-center">
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-ticket"></i>
                                                                            </div>
                                                                            <span class="text-145">Total tickets</span>
                                                                            <h6 class="coupon-status">20</h6>
                                                                        </div>
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-users"></i>
                                                                            </div>
                                                                            <span class="text-145">Ticket limit per customer</span>
                                                                            <h6 class="coupon-status">2</h6>
                                                                        </div>
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-cart-shopping"></i>
                                                                            </div>
                                                                            <span class="text-145">Discount</span>
                                                                            <h6 class="coupon-status">5%</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 price-ticket-card">
                                                                <div class="flex-wrap p-4 price-ticket-card-head d-md-flex align-items-start justify-content-between position-relative">
                                                                    <div class="d-flex align-items-center top-name">
                                                                        <div class="icon-box">
                                                                            <span class="icon-big rotate-icon icon icon-yellow">
                                                                                <i class="fa-solid fa-ticket"></i>
                                                                            </span>
                                                                            <h5 class="mt-1 mb-1 fs-16">Group - $10.00</h5>
                                                                            <p class="m-0 text-gray-50"><span class="visitor-date-time">May 3, 2022</span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="price-badge">
                                                                            <img src="images/discount.png" alt="">
                                                                        </div>
                                                                        <label class="mt-1 mb-0 btn-switch tfs-8 me-4">
                                                                            <input type="checkbox" value="" checked="">
                                                                            <span class="checkbox-slider"></span>
                                                                        </label>
                                                                        <div class="dropdown dropdown-default dropdown-text dropdown-icon-item">
                                                                            <button class="option-btn-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                <a href="#" class="dropdown-item"><i class="fa-solid fa-pen me-3"></i>Edit</a>
                                                                                <a href="#" class="dropdown-item"><i class="fa-solid fa-trash-can me-3"></i>Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="p-4 price-ticket-card-body border_top">
                                                                    <div class="flex-wrap full-width d-flex justify-content-between align-items-center">
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-ticket"></i>
                                                                            </div>
                                                                            <span class="text-145">Total tickets</span>
                                                                            <h6 class="coupon-status">Unlimited</h6>
                                                                        </div>
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-users"></i>
                                                                            </div>
                                                                            <span class="text-145">Ticket limit per customer</span>
                                                                            <h6 class="coupon-status">Unlimited</h6>
                                                                        </div>
                                                                        <div class="icon-box">
                                                                            <div class="icon me-3">
                                                                                <i class="fa-solid fa-cart-shopping"></i>
                                                                            </div>
                                                                            <span class="text-145">Discount</span>
                                                                            <h6 class="coupon-status">2%</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step-tab-panel step-tab-location" id="tab_step3">
                                    <div class="tab-from-content">
                                        <div class="main-card">
                                            <div class="bp-title">
                                                <h4><i class="fa-solid fa-gear step_icon me-3"></i>Setting</h4>
                                            </div>
                                            <div class="p_30 bp-form main-form">
                                                <div class="form-group">
                                                    <div class="ticket-section">
                                                        <label class="form-label fs-16">Let's configure a few additional options for your event!</label>
                                                        <p class="mt-2 mb-3 fs-14 d-block pe_right">Change the following settings based on your preferences to customise your event accordingly.</p>
                                                        <div class="content-holder">
                                                            <div class="pt-4 setting-item border_bottom pb_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="booking-start-time-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I want the bookings to start immediately.</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Disable this option if you want to start your booking from a specific date and time.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="booking-start-time-holder" style="display:none;">
                                                                    <div class="form-group pt_30">
                                                                        <label class="form-label fs-16">Booking starts on</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Specify the date and time when you want the booking to start.</p>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <label class="mt-3 form-label fs-6">Event Date.*</label>
                                                                                <div class="loc-group position-relative">
                                                                                    <input class="form-control h_50 datepicker-here" data-language="en" type="text" placeholder="MM/DD/YYYY" value="">
                                                                                    <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="clock-icon">
                                                                                    <label class="mt-3 form-label fs-6">Time</label>
                                                                                    <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" data-live-search="true">
                                                                                            <option value="00:00">12:00 AM</option>
                                                                                            <option value="00:15">12:15 AM</option>
                                                                                            <option value="00:30">12:30 AM</option>
                                                                                            <option value="00:45">12:45 AM</option>
                                                                                            <option value="01:00">01:00 AM</option>
                                                                                            <option value="01:15">01:15 AM</option>
                                                                                            <option value="01:30">01:30 AM</option>
                                                                                            <option value="01:45">01:45 AM</option>
                                                                                            <option value="02:00">02:00 AM</option>
                                                                                            <option value="02:15">02:15 AM</option>
                                                                                            <option value="02:30">02:30 AM</option>
                                                                                            <option value="02:45">02:45 AM</option>
                                                                                            <option value="03:00">03:00 AM</option>
                                                                                            <option value="03:15">03:15 AM</option>
                                                                                            <option value="03:30">03:30 AM</option>
                                                                                            <option value="03:45">03:45 AM</option>
                                                                                            <option value="04:00">04:00 AM</option>
                                                                                            <option value="04:15">04:15 AM</option>
                                                                                            <option value="04:30">04:30 AM</option>
                                                                                            <option value="04:45">04:45 AM</option>
                                                                                            <option value="05:00">05:00 AM</option>
                                                                                            <option value="05:15">05:15 AM</option>
                                                                                            <option value="05:30">05:30 AM</option>
                                                                                            <option value="05:45">05:45 AM</option>
                                                                                            <option value="06:00">06:00 AM</option>
                                                                                            <option value="06:15">06:15 AM</option>
                                                                                            <option value="06:30">06:30 AM</option>
                                                                                            <option value="06:45">06:45 AM</option>
                                                                                            <option value="07:00">07:00 AM</option>
                                                                                            <option value="07:15">07:15 AM</option>
                                                                                            <option value="07:30">07:30 AM</option>
                                                                                            <option value="07:45">07:45 AM</option>
                                                                                            <option value="08:00">08:00 AM</option>
                                                                                            <option value="08:15">08:15 AM</option>
                                                                                            <option value="08:30">08:30 AM</option>
                                                                                            <option value="08:45">08:45 AM</option>
                                                                                            <option value="09:00">09:00 AM</option>
                                                                                            <option value="09:15">09:15 AM</option>
                                                                                            <option value="09:30">09:30 AM</option>
                                                                                            <option value="09:45">09:45 AM</option>
                                                                                            <option value="10:00" selected="selected">10:00 AM</option>
                                                                                            <option value="10:15">10:15 AM</option>
                                                                                            <option value="10:30">10:30 AM</option>
                                                                                            <option value="10:45">10:45 AM</option>
                                                                                            <option value="11:00">11:00 AM</option>
                                                                                            <option value="11:15">11:15 AM</option>
                                                                                            <option value="11:30">11:30 AM</option>
                                                                                            <option value="11:45">11:45 AM</option>
                                                                                            <option value="12:00">12:00 PM</option>
                                                                                            <option value="12:15">12:15 PM</option>
                                                                                            <option value="12:30">12:30 PM</option>
                                                                                            <option value="12:45">12:45 PM</option>
                                                                                            <option value="13:00">01:00 PM</option>
                                                                                            <option value="13:15">01:15 PM</option>
                                                                                            <option value="13:30">01:30 PM</option>
                                                                                            <option value="13:45">01:45 PM</option>
                                                                                            <option value="14:00">02:00 PM</option>
                                                                                            <option value="14:15">02:15 PM</option>
                                                                                            <option value="14:30">02:30 PM</option>
                                                                                            <option value="14:45">02:45 PM</option>
                                                                                            <option value="15:00">03:00 PM</option>
                                                                                            <option value="15:15">03:15 PM</option>
                                                                                            <option value="15:30">03:30 PM</option>
                                                                                            <option value="15:45">03:45 PM</option>
                                                                                            <option value="16:00">04:00 PM</option>
                                                                                            <option value="16:15">04:15 PM</option>
                                                                                            <option value="16:30">04:30 PM</option>
                                                                                            <option value="16:45">04:45 PM</option>
                                                                                            <option value="17:00">05:00 PM</option>
                                                                                            <option value="17:15">05:15 PM</option>
                                                                                            <option value="17:30">05:30 PM</option>
                                                                                            <option value="17:45">05:45 PM</option>
                                                                                            <option value="18:00">06:00 PM</option>
                                                                                            <option value="18:15">06:15 PM</option>
                                                                                            <option value="18:30">06:30 PM</option>
                                                                                            <option value="18:45">06:45 PM</option>
                                                                                            <option value="19:00">07:00 PM</option>
                                                                                            <option value="19:15">07:15 PM</option>
                                                                                            <option value="19:30">07:30 PM</option>
                                                                                            <option value="19:45">07:45 PM</option>
                                                                                            <option value="20:00">08:00 PM</option>
                                                                                            <option value="20:15">08:15 PM</option>
                                                                                            <option value="20:30">08:30 PM</option>
                                                                                            <option value="20:45">08:45 PM</option>
                                                                                            <option value="21:00">09:00 PM</option>
                                                                                            <option value="21:15">09:15 PM</option>
                                                                                            <option value="21:30">09:30 PM</option>
                                                                                            <option value="21:45">09:45 PM</option>
                                                                                            <option value="22:00">10:00 PM</option>
                                                                                            <option value="22:15">10:15 PM</option>
                                                                                            <option value="22:30">10:30 PM</option>
                                                                                            <option value="22:45">10:45 PM</option>
                                                                                            <option value="23:00">11:00 PM</option>
                                                                                            <option value="23:15">11:15 PM</option>
                                                                                            <option value="23:30">11:30 PM</option>
                                                                                            <option value="23:45">11:45 PM</option>
                                                                                        </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-9" aria-haspopup="listbox" aria-expanded="false" title="10:00 AM">
                                                                                            <div class="filter-option">
                                                                                                <div class="filter-option-inner">
                                                                                                    <div class="filter-option-inner-inner">10:00 AM</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </button>
                                                                                        <div class="dropdown-menu ">
                                                                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-9" aria-autocomplete="list"></div>
                                                                                            <div class="inner show" role="listbox" id="bs-select-9" tabindex="-1">
                                                                                                <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="setting-item border_bottom pb_30 pt_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="booking-end-time-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I want the bookings to continue until my event ends.</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Disable this option if you want to end your booking from a specific date and time.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="booking-end-time-holder" style="display:none;">
                                                                    <div class="form-group pt_30">
                                                                        <label class="form-label fs-16">Booking ends on</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Specify the date and time when you want the booking to start.</p>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <label class="mt-3 form-label fs-6">Event Date.*</label>
                                                                                <div class="loc-group position-relative">
                                                                                    <input class="form-control h_50 datepicker-here" data-language="en" type="text" placeholder="MM/DD/YYYY" value="">
                                                                                    <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="clock-icon">
                                                                                    <label class="mt-3 form-label fs-6">Time</label>
                                                                                    <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" data-live-search="true">
                                                                                            <option value="00:00">12:00 AM</option>
                                                                                            <option value="00:15">12:15 AM</option>
                                                                                            <option value="00:30">12:30 AM</option>
                                                                                            <option value="00:45">12:45 AM</option>
                                                                                            <option value="01:00">01:00 AM</option>
                                                                                            <option value="01:15">01:15 AM</option>
                                                                                            <option value="01:30">01:30 AM</option>
                                                                                            <option value="01:45">01:45 AM</option>
                                                                                            <option value="02:00">02:00 AM</option>
                                                                                            <option value="02:15">02:15 AM</option>
                                                                                            <option value="02:30">02:30 AM</option>
                                                                                            <option value="02:45">02:45 AM</option>
                                                                                            <option value="03:00">03:00 AM</option>
                                                                                            <option value="03:15">03:15 AM</option>
                                                                                            <option value="03:30">03:30 AM</option>
                                                                                            <option value="03:45">03:45 AM</option>
                                                                                            <option value="04:00">04:00 AM</option>
                                                                                            <option value="04:15">04:15 AM</option>
                                                                                            <option value="04:30">04:30 AM</option>
                                                                                            <option value="04:45">04:45 AM</option>
                                                                                            <option value="05:00">05:00 AM</option>
                                                                                            <option value="05:15">05:15 AM</option>
                                                                                            <option value="05:30">05:30 AM</option>
                                                                                            <option value="05:45">05:45 AM</option>
                                                                                            <option value="06:00">06:00 AM</option>
                                                                                            <option value="06:15">06:15 AM</option>
                                                                                            <option value="06:30">06:30 AM</option>
                                                                                            <option value="06:45">06:45 AM</option>
                                                                                            <option value="07:00">07:00 AM</option>
                                                                                            <option value="07:15">07:15 AM</option>
                                                                                            <option value="07:30">07:30 AM</option>
                                                                                            <option value="07:45">07:45 AM</option>
                                                                                            <option value="08:00">08:00 AM</option>
                                                                                            <option value="08:15">08:15 AM</option>
                                                                                            <option value="08:30">08:30 AM</option>
                                                                                            <option value="08:45">08:45 AM</option>
                                                                                            <option value="09:00">09:00 AM</option>
                                                                                            <option value="09:15">09:15 AM</option>
                                                                                            <option value="09:30">09:30 AM</option>
                                                                                            <option value="09:45">09:45 AM</option>
                                                                                            <option value="10:00" selected="selected">10:00 AM</option>
                                                                                            <option value="10:15">10:15 AM</option>
                                                                                            <option value="10:30">10:30 AM</option>
                                                                                            <option value="10:45">10:45 AM</option>
                                                                                            <option value="11:00">11:00 AM</option>
                                                                                            <option value="11:15">11:15 AM</option>
                                                                                            <option value="11:30">11:30 AM</option>
                                                                                            <option value="11:45">11:45 AM</option>
                                                                                            <option value="12:00">12:00 PM</option>
                                                                                            <option value="12:15">12:15 PM</option>
                                                                                            <option value="12:30">12:30 PM</option>
                                                                                            <option value="12:45">12:45 PM</option>
                                                                                            <option value="13:00">01:00 PM</option>
                                                                                            <option value="13:15">01:15 PM</option>
                                                                                            <option value="13:30">01:30 PM</option>
                                                                                            <option value="13:45">01:45 PM</option>
                                                                                            <option value="14:00">02:00 PM</option>
                                                                                            <option value="14:15">02:15 PM</option>
                                                                                            <option value="14:30">02:30 PM</option>
                                                                                            <option value="14:45">02:45 PM</option>
                                                                                            <option value="15:00">03:00 PM</option>
                                                                                            <option value="15:15">03:15 PM</option>
                                                                                            <option value="15:30">03:30 PM</option>
                                                                                            <option value="15:45">03:45 PM</option>
                                                                                            <option value="16:00">04:00 PM</option>
                                                                                            <option value="16:15">04:15 PM</option>
                                                                                            <option value="16:30">04:30 PM</option>
                                                                                            <option value="16:45">04:45 PM</option>
                                                                                            <option value="17:00">05:00 PM</option>
                                                                                            <option value="17:15">05:15 PM</option>
                                                                                            <option value="17:30">05:30 PM</option>
                                                                                            <option value="17:45">05:45 PM</option>
                                                                                            <option value="18:00">06:00 PM</option>
                                                                                            <option value="18:15">06:15 PM</option>
                                                                                            <option value="18:30">06:30 PM</option>
                                                                                            <option value="18:45">06:45 PM</option>
                                                                                            <option value="19:00">07:00 PM</option>
                                                                                            <option value="19:15">07:15 PM</option>
                                                                                            <option value="19:30">07:30 PM</option>
                                                                                            <option value="19:45">07:45 PM</option>
                                                                                            <option value="20:00">08:00 PM</option>
                                                                                            <option value="20:15">08:15 PM</option>
                                                                                            <option value="20:30">08:30 PM</option>
                                                                                            <option value="20:45">08:45 PM</option>
                                                                                            <option value="21:00">09:00 PM</option>
                                                                                            <option value="21:15">09:15 PM</option>
                                                                                            <option value="21:30">09:30 PM</option>
                                                                                            <option value="21:45">09:45 PM</option>
                                                                                            <option value="22:00">10:00 PM</option>
                                                                                            <option value="22:15">10:15 PM</option>
                                                                                            <option value="22:30">10:30 PM</option>
                                                                                            <option value="22:45">10:45 PM</option>
                                                                                            <option value="23:00">11:00 PM</option>
                                                                                            <option value="23:15">11:15 PM</option>
                                                                                            <option value="23:30">11:30 PM</option>
                                                                                            <option value="23:45">11:45 PM</option>
                                                                                        </select><button type="button" tabindex="-1" class="btn dropdown-toggle btn-light" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-10" aria-haspopup="listbox" aria-expanded="false" title="10:00 AM">
                                                                                            <div class="filter-option">
                                                                                                <div class="filter-option-inner">
                                                                                                    <div class="filter-option-inner-inner">10:00 AM</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </button>
                                                                                        <div class="dropdown-menu ">
                                                                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-10" aria-autocomplete="list"></div>
                                                                                            <div class="inner show" role="listbox" id="bs-select-10" tabindex="-1">
                                                                                                <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="setting-item border_bottom pb_30 pt_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="passing-service-charge-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I want my customers to pay the applicable service fees at the time when they make the bookings.</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block pe_right">Passing your service charge means your attendees will pay your service charge in addition to the ticket price. <a href="#" class="a-link">Learn more</a></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="setting-item border_bottom pb_30 pt_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="refund-policies-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I do not wish to offer my customers with option to cancel their orders and receive refund.</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Disable this slider if you want to let your customers cancel their order and select a refund policy.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="refund-policies-holder" style="display:none;">
                                                                    <div class="mt-4 refund-policies-content border_top">
                                                                        <div class="row grid-padding-8">
                                                                            <div class="mb-6 col-md-12">
                                                                                <div class="refund-method">
                                                                                    <div class="mb-0 form-group">
                                                                                        <label class="mt-4 mb-0 brn-checkbox-radio">
                                                                                            <input type="radio" required="" name="refund_policy_id" value="refund-id-1" class="form-check-input br-checkbox refund-policy1">
                                                                                            <span class="fs-14 fw-bold ms-xl-2">I wish to offer my customers with option to cancel their orders. However, I will handle refund manually.</span>
                                                                                            <span class="mt-2 mb-4 ms-xl-4 d-block sub-label">Select this option if you want to refund your customer manually.</span>
                                                                                        </label>
                                                                                        <div class="refund-input-content" data-method="refund-id-1">
                                                                                            <div class="mb-3 input-content">
                                                                                                <label class="mb-2 color-black fs-14 fw-bold">Cancellation must be made<span class="red">*</span></label>
                                                                                                <div class="flex-wrap d-block d-md-flex align-items-center flex-lg-wrap-reverse">
                                                                                                    <div class="pl-0 col-md-4">
                                                                                                        <div class="mr-3 input-group mx-width-135 input-number">
                                                                                                            <input type="number" min="0" max="30" class="form-control" placeholder="">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="mt-3 mb-3 input-sign ms-md-3">days before the event</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="refund-method">
                                                                                    <label class="mt-4 mb-0 brn-checkbox-radio">
                                                                                        <input type="radio" name="refund_policy_id" value="refund-id-2" class="form-check-input br-checkbox refund-polic-2">
                                                                                        <span class="fs-14 fw-bold ms-xl-2">I wish to offer my customers with option to cancel their orders and receive refund automatically.</span>
                                                                                        <span class="mt-2 mb-4 ms-xl-4 d-block sub-label">Select this option if you want to refund your customer automatically.</span>
                                                                                    </label>
                                                                                    <div class="refund-input-content" data-method="refund-id-2">
                                                                                        <div class="mb-3 input-content">
                                                                                            <label class="mb-2 color-black fs-14 fw-bold">Cancellation must be made <span class="red">*</span></label>
                                                                                            <div class="flex-wrap d-block d-md-flex align-items-center flex-lg-wrap-reverse">
                                                                                                <div class="col-md-4">
                                                                                                    <div class="input-group input-number">
                                                                                                        <input type="number" min="0" max="30" class="form-control" placeholder="">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mt-3 mb-3 input-sign ms-md-3">days before the event</div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="mb-3 input-content">
                                                                                            <label class="mb-2 color-black fs-14 fw-bold">Refund amount <span class="red">*</span></label>
                                                                                            <div class="flex-wrap d-block d-md-flex align-items-center flex-lg-wrap-reverse">
                                                                                                <div class="col-md-4">
                                                                                                    <div class="input-group loc-group position-relative">
                                                                                                        <input type="text" value="" class="form-control" placeholder="">
                                                                                                        <span class="percentage-icon"><i class="fa-solid fa-percent"></i></span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mt-3 mb-3 input-sign ms-md-3">days before the event</div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="setting-item border_bottom pb_30 pt_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="ticket-instructions-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I do not require adding any special instructions on the tickets.</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Use this space to provide any last minute checklists your attendees must know in order to attend your event. Anything you provide here will be printed on your ticket.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="ticket-instructions-holder" style="display:none;">
                                                                    <div class="mt-4 ticket-instructions-content">
                                                                        <textarea class="form-textarea" placeholder="About"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="pb-0 setting-item pt_30">
                                                                <div class="d-flex align-items-start">
                                                                    <label class="m-0 btn-switch me-3">
                                                                        <input type="checkbox" class="" id="tags-btn" value="" checked="">
                                                                        <span class="checkbox-slider"></span>
                                                                    </label>
                                                                    <div class="d-flex flex-column">
                                                                        <label class="mb-1 color-black fw-bold">I do not want to add tags in my event</label>
                                                                        <p class="mt-2 mb-0 fs-14 d-block">Use relevant words as your tags to improve the discoverability of your event. <a href="#" class="a-link">Learn more</a></p>
                                                                    </div>
                                                                </div>
                                                                <div class="tags-holder" style="display:none;">
                                                                    <div class="mt-4 ticket-instructions-content tags-container">
                                                                        <input class="form-control tags-input" type="text" placeholder="Type your tags and press enter">
                                                                        <div class="tags-list">
                                                                            <!-- keywords go here -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 step-footer step-tab-pager">
                                <button data-direction="prev" class="btn btn-default btn-hover steps_btn" style="display: none;">Previous</button>
                                <button data-direction="next" class="btn btn-default btn-hover steps_btn">Next</button>
                                <button data-direction="finish" class="btn btn-default btn-hover steps_btn" style="display: none;">Create</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-frontend-app-layout>