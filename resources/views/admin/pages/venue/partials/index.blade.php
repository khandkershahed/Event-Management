<x-admin-app-layout :title="__('Venues')">

    @push('scripts')
        <script>
            $(document).ready(function() {
                if (getURLParameter('directory') != "null") {
                    $('input[type="radio"][name="directory"][value="' + getURLParameter('directory') + '"]').attr(
                        "checked", "checked");
                }
            });
        </script>
    @endpush

    @php
        $pagetitle = auth()->user()->hasRole('ROLE_ADMINISTRATOR') ? __('Manage venues') : __('My venues');
        $navigation = [['dashboard_index' => __('Dashboard'), 'current' => $pagetitle]];
    @endphp

    @section('title', $pagetitle)

    @include('global.navigation', ['navigation' => $navigation])

    <section class="section-content padding-y bg-white">
        <div class="{{ setting('app_layout') }}">
            <div class="row">
                <aside class="col-lg-3 pt-3 pt-lg-0">
                    @include('dashboard.sidebar')
                </aside>
                <div class="col-lg-9 mt-4 mt-lg-0">

                    <div class="box shadow-none bg-gray mb-4">
                        <div class="row">
                            <div class="col-sm-12 col-lg-2 text-center text-lg-left mb-3 mb-lg-0">
                                <span class="center-lg-y text-muted">
                                    {{ trans_choice(':count result(s) found', $venues->total(), ['count' => $venues->total()]) }}
                                </span>
                            </div>
                            <div class="col-sm-12 col-lg-4 text-center mb-3 mb-lg-0">
                                <form>
                                    <ul class="list-inline">
                                        <li class="list-inline-item dropdown">
                                            <a href="#" class="dropdown-toggle"
                                                data-toggle="dropdown">{{ __('Search') }}</a>
                                            <div class="dropdown-menu dropdown-menu-arrow p-3"
                                                style="min-width:300px;max-width:500px;">
                                                <label for="keyword">{{ __('Keyword') }}</label>
                                                <input id="keyword" name="keyword" type="text" class="form-control"
                                                    value="{{ request('keyword') }}">
                                            </div>
                                        </li>
                                        @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
                                            <li class="list-inline-item dropdown">
                                                <a href="#" class="dropdown-toggle"
                                                    data-toggle="dropdown">{{ __('Listed on directory') }}</a>
                                                <div class="dropdown-menu dropdown-menu-arrow p-3"
                                                    style="min-width:450px;max-width:550px;">
                                                    @foreach (['all' => 'All', '1' => 'Yes', '0' => 'No'] as $value => $label)
                                                        <div
                                                            class="custom-control custom-checkbox custom-control-inline">
                                                            <input type="radio" class="custom-control-input"
                                                                id="directory-{{ $value }}" name="directory"
                                                                value="{{ $value }}">
                                                            <label class="custom-control-label"
                                                                for="directory-{{ $value }}">{{ __($label) }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endif
                                        <li class="list-inline-item ml-3">
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fas fa-search"></i></button>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                            <div class="col-sm-12 col-lg-6 text-center text-lg-right">
                                <label for="sortable-select" class="display-inline">
                                    {{ __('Sort by') }}
                                    <select id="sortable-select"
                                        class="form-control display-inline-block bg-white select2 ml-3"
                                        style="width: 50%;">
                                        <option value="desc">{{ __('Creation date (desc)') }}</option>
                                        <option value="asc">{{ __('Creation date (asc)') }}</option>
                                    </select>
                                </label>
                                @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
                                    <a href="{{ route('dashboard_administrator_venue_add') }}"
                                        class="btn btn-primary ml-3" title="{{ __('Add a new venue') }}">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @elseif(auth()->user()->hasRole('ROLE_ORGANIZER'))
                                    <a href="{{ route('dashboard_organizer_venue_add') }}" class="btn btn-primary ml-3"
                                        title="{{ __('Add a new venue') }}">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($venues->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-vcenter text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Image') }}</th>
                                                    @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
                                                        <th>{{ __('Organizer') }}</th>
                                                    @endif
                                                    <th>{{ __('Events count') }}</th>
                                                    <th>{{ __('Seating plans') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th class="text-center"><i class="fas fa-cog"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($venues as $venue)
                                                    <tr>
                                                        <td>
                                                            <a
                                                                href="{{ route('venue', ['slug' => $venue->slug]) }}">{{ $venue->name }}</a>
                                                        </td>
                                                        <td>
                                                            <a class="fancybox"
                                                                href="{{ $venue->getFirstImageOrPlaceholder() }}"
                                                                title="{{ __('Enlarge') }}">
                                                                <img src="{{ $venue->getFirstImageOrPlaceholder() }}"
                                                                    class="img-thumbnail img-50-50" />
                                                            </a>
                                                        </td>
                                                        @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
                                                            <td>{{ $venue->organizer->name ?? __('Added by administrator') }}
                                                            </td>
                                                        @endif
                                                        <td>{{ count($venue->eventdates) }}</td>
                                                        <td>{{ count($venue->seatingPlans) }}</td>
                                                        <td>
                                                            @if ($venue->hidden)
                                                                <span class="badge badge-danger"><i
                                                                        class="fas fa-eye-slash fa-fw"></i>
                                                                    {{ __('Hidden') }}</span>
                                                            @else
                                                                <span class="badge badge-success"><i
                                                                        class="fas fa-eye fa-fw"></i>
                                                                    {{ __('Visible') }}</span>
                                                            @endif
                                                            <br><br>
                                                            @if ($venue->listedondirectory)
                                                                <span class="badge badge-success"><i
                                                                        class="fas fa-eye fa-fw"></i>
                                                                    {{ __('Listed on the directory') }}</span>
                                                            @else
                                                                <span class="badge badge-danger"><i
                                                                        class="fas fa-eye-slash fa-fw"></i>
                                                                    {{ __('Not listed on the directory') }}</span>
                                                            @endif
                                                            @if ($venue->deletedAt)
                                                                <br><br>
                                                                <span class="badge badge-danger"><i
                                                                        class="fas fa-times fa-fw"></i>
                                                                    {{ __('Deleted') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="item-action dropdown">
                                                                <a href="#" data-toggle="dropdown"
                                                                    class="icon"><i class="fas fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR'))
                                                                        <a href="{{ route('dashboard_administrator_venue_edit', ['slug' => $venue->slug]) }}"
                                                                            class="dropdown-item"><i
                                                                                class="dropdown-icon fas fa-edit fa-fw text-muted"></i>
                                                                            {{ __('Edit') }}</a>
                                                                        @if ($venue->hidden)
                                                                            <a href="{{ route('dashboard_administrator_venue_show', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-eye fa-fw text-muted"></i>
                                                                                {{ __('Show') }}</a>
                                                                        @else
                                                                            <a href="{{ route('dashboard_administrator_venue_hide', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-eye-slash fa-fw text-muted"></i>
                                                                                {{ __('Hide') }}</a>
                                                                        @endif
                                                                        @if ($venue->listedondirectory)
                                                                            <a href="{{ route('dashboard_administrator_venue_hidefromdirectory', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-minus-square fa-fw text-muted"></i>
                                                                                {{ __('Hide from public directory') }}</a>
                                                                        @else
                                                                            <a href="{{ route('dashboard_administrator_venue_listondirectory', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-plus-square fa-fw text-muted"></i>
                                                                                {{ __('List on public directory') }}</a>
                                                                        @endif
                                                                        @if ($venue->deletedAt)
                                                                            <a href="{{ route('dashboard_administrator_venue_restore', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-trash-restore fa-fw text-muted"></i>
                                                                                {{ __('Restore') }}</a>
                                                                            <span
                                                                                data-target="{{ route('dashboard_administrator_venue_delete', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item requires-confirmation"
                                                                                data-confirmation-text="{{ __('You are about to delete the venue PERMANENTLY') }}"><i
                                                                                    class="dropdown-icon fas fa-trash fa-fw text-muted"></i>
                                                                                {{ __('Delete permanently') }}</span>
                                                                        @else
                                                                            <a href="{{ route('dashboard_administrator_venue_disable', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-times fa-fw text-muted"></i>
                                                                                {{ __('Delete') }}</a>
                                                                        @endif
                                                                    @elseif(auth()->user()->hasRole('ROLE_ORGANIZER'))
                                                                        <a href="{{ route('dashboard_organizer_venue_edit', ['slug' => $venue->slug]) }}"
                                                                            class="dropdown-item"><i
                                                                                class="dropdown-icon fas fa-edit fa-fw text-muted"></i>
                                                                            {{ __('Edit') }}</a>
                                                                        <a href="{{ route('dashboard_organizer_venue_seating_plans', ['venueSlug' => $venue->slug]) }}"
                                                                            class="dropdown-item"><i
                                                                                class="dropdown-icon fas fa-circle fa-fw text-muted"></i>
                                                                            {{ __('Seating plans') }}</a>
                                                                        @if ($venue->hidden)
                                                                            <a href="{{ route('dashboard_organizer_venue_show', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-eye fa-fw text-muted"></i>
                                                                                {{ __('Show') }}</a>
                                                                        @else
                                                                            <a href="{{ route('dashboard_organizer_venue_hide', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-eye-slash fa-fw text-muted"></i>
                                                                                {{ __('Hide') }}</a>
                                                                        @endif
                                                                        @if ($venue->deletedAt)
                                                                            <span
                                                                                data-target="{{ route('dashboard_organizer_venue_delete', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item requires-confirmation"
                                                                                data-confirmation-text="{{ __('You are about to delete the venue PERMANENTLY') }}"><i
                                                                                    class="dropdown-icon fas fa-trash fa-fw text-muted"></i>
                                                                                {{ __('Delete permanently') }}</span>
                                                                        @else
                                                                            <a href="{{ route('dashboard_organizer_venue_disable', ['slug' => $venue->slug]) }}"
                                                                                class="dropdown-item"><i
                                                                                    class="dropdown-icon fas fa-times fa-fw text-muted"></i>
                                                                                {{ __('Delete') }}</a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{ $venues->links() }}
                            </div>
                        </div>
                    @else
                        @include('global.message', [
                            'type' => 'info',
                            'message' => __('No venues found'),
                            'icon' => 'fas fa-exclamation-circle',
                        ])
                    @endif

                </div>
            </div>
        </div>
    </section>
</x-admin-app-layout>
