<x-admin-app-layout :title="optional($venue)->id ? __('Update the venue') : __('Add a new venue')">

    @php
        $pagetitle = optional($venue)->id ? __('Update the venue') : __('Add a new venue');
    @endphp

    @push('breadcrumbs')
        @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
            @include('components.breadcrumbs', [
                'items' => [
                    ['route' => 'dashboard.index', 'label' => __('Dashboard')],
                    ['route' => 'dashboard.administrator.venue', 'label' => __('Manage venues')],
                    ['label' => $pagetitle],
                ],
            ])
        @elseif(auth()->user()->hasRole('ROLE_ORGANIZER'))
            @include('components.breadcrumbs', [
                'items' => [
                    ['route' => 'dashboard.index', 'label' => __('Dashboard')],
                    ['route' => 'dashboard.organizer.venue', 'label' => __('My venues')],
                    ['label' => $pagetitle],
                ],
            ])
        @endif
    @endpush

    <section class="section-content padding-y bg-white">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{ optional($venue)->id ? route('admin.venue.update', optional($venue)->id) : route('admin.venue.store') }}"
                            method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @if (optional($venue)->id)
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label class="required" for="type">Type</label>
                                <select id="type" name="type" class="form-control">
                                    <option value="1">Banquet Hall</option>
                                    <option value="2">Bar</option>
                                    <option value="3">Boat</option>
                                    <option value="4">Brewery</option>
                                    <option value="5">Cafe</option>
                                    <option value="6">Co-working space</option>
                                    <option value="7">Conference center</option>
                                    <option value="8">Country Club</option>
                                    <option value="9">Event Space</option>
                                    <option value="10">Gallery</option>
                                    <option value="11">Gym</option>
                                    <option value="12">Hotel</option>
                                    <option value="13">Loft</option>
                                    <option value="14">Meeting space</option>
                                    <option value="15">Museum</option>
                                    <option value="16">Restaurant</option>
                                    <option value="17">Stadium</option>
                                    <option value="18">Theater</option>
                                    <option value="19">Other</option>
                                </select>
                            </div>

                            {{-- Amenities --}}
                            <fieldset class="form-group">
                                <legend class="col-form-label">Amenities</legend>
                                @php
                                    $amenities = [
                                        6 => 'Beachfront',
                                        12 => 'A/V Equipment',
                                        8 => 'Handicap Accessible',
                                        10 => 'Pet Friendly',
                                        9 => 'Outdoor Space',
                                        13 => 'Breakout rooms',
                                        7 => 'Business Center',
                                        18 => 'Cab',
                                        16 => 'Rooftop',
                                        17 => 'Theater space',
                                        14 => 'Parking',
                                        15 => 'Media room',
                                        5 => 'Spa',
                                        11 => 'WiFi',
                                    ];
                                @endphp

                                @foreach ($amenities as $key => $label)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" id="amenity_{{ $key }}" name="amenities[]"
                                            value="{{ $key }}" class="custom-control-input">
                                        <label class="custom-control-label"
                                            for="amenity_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </fieldset>

                            {{-- Seated Guests --}}
                            <div class="form-group">
                                <label for="seatedguests">Seated guests number</label>
                                <input type="number" name="seatedguests" id="seatedguests" class="form-control"
                                    max="100000">
                            </div>

                            {{-- Standing Guests --}}
                            <div class="form-group">
                                <label for="standingguests">Standing guests number</label>
                                <input type="number" name="standingguests" id="standingguests" class="form-control"
                                    max="100000">
                            </div>

                            {{-- Neighborhoods --}}
                            <div class="form-group">
                                <label for="neighborhoods">Neighborhoods</label>
                                <input type="text" name="neighborhoods" id="neighborhoods" class="form-control">
                            </div>

                            {{-- Pricing --}}
                            <div class="form-group">
                                <label for="pricing">Pricing</label>
                                <textarea name="pricing" id="pricing" class="form-control"></textarea>
                            </div>

                            {{-- Availability --}}
                            <div class="form-group">
                                <label for="availibility">Availability</label>
                                <textarea name="availibility" id="availibility" class="form-control"></textarea>
                            </div>

                            {{-- Food & Beverage --}}
                            <div class="form-group">
                                <label for="foodbeverage">Food and Beverage Details</label>
                                <textarea name="foodbeverage" id="foodbeverage" class="form-control"></textarea>
                            </div>

                            {{-- Quote Form --}}
                            <fieldset class="form-group">
                                <legend class="col-form-label required">Show the quote form on the venue page</legend>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="quoteform_no" name="quoteform" value="0"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="quoteform_no">No</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="quoteform_yes" name="quoteform" value="1"
                                            class="custom-control-input" checked>
                                        <label class="custom-control-label" for="quoteform_yes">Yes</label>
                                    </div>
                                </div>
                            </fieldset>

                            {{-- Contact Email --}}
                            <div class="form-group">
                                <label for="contactemail">Contact Email</label>
                                <small class="form-text text-muted mb-3">
                                    <i class="fas fa-info-circle text-primary mr-1"></i>
                                    This email address will be used to receive the quote requests.
                                </small>
                                <input type="email" name="contactemail" id="contactemail" class="form-control">
                            </div>

                            {{-- Address Fields --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="street">Street Address</label>
                                        <input type="text" name="street" id="street" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="street2">Street Address 2</label>
                                        <input type="text" name="street2" id="street2" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" name="city" id="city" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="postalcode">Postal Code</label>
                                        <input type="text" name="postalcode" id="postalcode" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" name="state" id="state" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select name="country" id="country" class="form-control select2" required>
                                            <option value="">Select an option</option>
                                            <option value="228">Afghanistan</option>
                                            <option value="86">South Africa</option>
                                            <option value="85">Egypt</option>
                                            <option value="243">Aland Islands</option>
                                            <option value="234">Albania</option>
                                            <option value="195">Germany</option>
                                            <option value="197">Algeria</option>
                                        </select>
                                    </div>

                                    <fieldset class="form-group">
                                        <legend class="col-form-label required">Show Map on Venue Page</legend>
                                        <div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="showmap_no" name="showmap" value="0"
                                                    class="custom-control-input">
                                                <label class="custom-control-label" for="showmap_no">No</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="showmap_yes" name="showmap" value="1"
                                                    class="custom-control-input" checked>
                                                <label class="custom-control-label" for="showmap_yes">Yes</label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <div class="sticky-top sticky-sidebar pt-0 pt-lg-6">
                                        <iframe width="100%" height="300" class="venue-map"
                                            style="display: none"
                                            src="https://maps.google.com/maps?q=university%20of%20san%20francisco&z=13&output=embed"
                                            frameborder="0" scrolling="no" marginheight="0"
                                            marginwidth="0"></iframe>
                                    </div>
                                </div>
                            </div>

                            {{-- Images (you can customize collection logic if needed) --}}
                            <div class="form-group">
                                <label for="images">Images</label>
                                <input type="file" name="images[]" multiple class="form-control">
                            </div>

                            {{-- Hidden Lat/Lng --}}
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">

                            {{-- Submit Button --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        @if (!empty(config('services.google.maps_api_key')))
            <script src="https://maps.google.com/maps/api/js?sensor=false&key={{ config('services.google.maps_api_key') }}">
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapFrame = document.querySelector('.venue-map');

                function getVenueAddress() {
                    let address = '';
                    address += document.getElementById('venue_translations_en_name')?.value ?? '';
                    address += ' ' + (document.getElementById('venue_street')?.value ?? '');
                    address += ' ' + (document.getElementById('venue_street2')?.value ?? '');
                    address += ' ' + (document.getElementById('venue_city')?.value ?? '');
                    address += ' ' + (document.getElementById('venue_postalcode')?.value ?? '');
                    address += ' ' + (document.getElementById('venue_state')?.value ?? '');
                    address += ' ' + (document.getElementById('venue_country')?.selectedOptions[0]?.text ?? '');
                    return address.trim();
                }

                function updateMap() {
                    const address = getVenueAddress();
                    if (address) {
                        mapFrame.src =
                            `{{ request()->getScheme() }}://maps.google.com/maps?q=${encodeURIComponent(address)}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
                        mapFrame.classList.remove('d-none');
                    }
                }

                document.querySelectorAll('#venue_street, #venue_street2, #venue_city, #venue_postalcode, #venue_state')
                    .forEach(el => el.addEventListener('blur', updateMap));

                document.getElementById('venue_country')?.addEventListener('blur', updateMap);

                @if (optional($venue)->id)
                    updateMap();
                @endif

                @if (!empty(config('services.google.maps_api_key')))
                    document.getElementById('venue_save')?.addEventListener('click', function(e) {
                        const address = getVenueAddress();
                        if (address) {
                            e.preventDefault();
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({
                                'address': address
                            }, function(results, status) {
                                if (status === google.maps.GeocoderStatus.OK) {
                                    document.getElementById('venue_lat')?.value = results[0].geometry
                                        .location.lat();
                                    document.getElementById('venue_lng')?.value = results[0].geometry
                                        .location.lng();
                                }
                                e.target.closest('form').submit();
                            });
                        }
                    });
                @endif
            });
        </script>
    @endpush
</x-admin-app-layout>
