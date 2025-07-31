<div class="row py-10">
    <div class="col-lg-8 mb-4 bg-light mx-auto">
        <div class="row border-info gx-2 py-2 align-items-center px-3 rounded-2">
            <div class="col-lg-12 pt-3">
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="monday">
                                <input class="form-check-input ms-3" type="checkbox" name="monday"
                                    @checked(optional($setting)->monday == '1') value="1" id="monday_checkbox" />
                                <span class="ps-3">Monday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_monday" type="time" class="form-control" name="start_time_monday"
                            value="{{ optional($setting)->start_time_monday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_monday" type="time" class="form-control" name="end_time_monday"
                            value="{{ optional($setting)->end_time_monday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="tuesday">
                                <input class="form-check-input ms-3" type="checkbox" name="tuesday"
                                    @checked(optional($setting)->tuesday == '1') value="1" id="tuesday_checkbox" />
                                <span class="ps-3">Tuesday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_tuesday" type="time" class="form-control" name="start_time_tuesday"
                            value="{{ optional($setting)->start_time_tuesday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_tuesday" type="time" class="form-control" name="end_time_tuesday"
                            value="{{ optional($setting)->end_time_tuesday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="wednesday">
                                <input class="form-check-input ms-3" type="checkbox" name="wednesday"
                                    @checked(optional($setting)->wednesday == '1') value="1" id="wednesday_checkbox" />
                                <span class="ps-3">Wednesday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_wednesday" type="time" class="form-control" name="start_time_wednesday"
                            value="{{ optional($setting)->start_time_wednesday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_wednesday" type="time" class="form-control" name="end_time_wednesday"
                            value="{{ optional($setting)->end_time_wednesday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="thursday">
                                <input class="form-check-input ms-3" type="checkbox" name="thursday"
                                    @checked(optional($setting)->thursday == '1') value="1" id="thursday_checkbox" />
                                <span class="ps-3">Thursday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_thursday" type="time" class="form-control" name="start_time_thursday"
                            value="{{ optional($setting)->start_time_thursday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_thursday" type="time" class="form-control" name="end_time_thursday"
                            value="{{ optional($setting)->end_time_thursday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="friday">
                                <input class="form-check-input ms-3" type="checkbox" name="friday"
                                    @checked(optional($setting)->friday == '1') value="1" id="friday_checkbox" />
                                <span class="ps-3">friday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_friday" type="time" class="form-control" name="start_time_friday"
                            value="{{ optional($setting)->start_time_friday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_friday" type="time" class="form-control" name="end_time_friday"
                            value="{{ optional($setting)->end_time_friday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="saturday">
                                <input class="form-check-input ms-3" type="checkbox" name="saturday"
                                    @checked(optional($setting)->saturday == '1') value="1" id="saturday_checkbox" />
                                <span class="ps-3">Saturday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_saturday" type="time" class="form-control"
                            name="start_time_saturday"
                            value="{{ optional($setting)->start_time_saturday }}"
                            placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_saturday" type="time" class="form-control" name="end_time_saturday"
                            value="{{ optional($setting)->end_time_saturday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
                <div class="row align-items-center pb-3">
                    <div class="col-lg-4">
                        <div class="form-check ps-0 border bg-light-primary p-3 rounded-2 text-center text-info"
                            style="text-align: start !important;">
                            <label class="form-check-label" for="sunday">
                                <input class="form-check-input ms-3" type="checkbox" name="sunday"
                                    @checked(optional($setting)->sunday == '1') value="1" id="sunday_checkbox" />
                                <span class="ps-3">sunday</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <input id="start_time_sunday" type="time" class="form-control" name="start_time_sunday"
                            value="{{ optional($setting)->start_time_sunday }}" placeholder="Enter time" />
                    </div>
                    <div class="col-lg-4">
                        <input id="end_time_sunday" type="time" class="form-control" name="end_time_sunday"
                            value="{{ optional($setting)->end_time_sunday }}" placeholder="Enter time" />
                    </div>
                </div>
                <!-- Repeat for other days as needed -->
            </div>
        </div>
    </div>
</div>
