<!-- ==========================================================
     MODAL: RENAME
     ========================================================== -->
<div class="modal fade" id="modal-rename">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5>Rename Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">New Name</label>
                <input type="text" class="form-control" id="modal-rename-input">
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="modal-rename-save">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- ==========================================================
     MODAL: BACKGROUND COLOR
     ========================================================== -->
<div class="modal fade" id="modal-bgcolor">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5>Background Color</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">Choose Color (HEX or RGB)</label>
                <input type="text" id="modal-bgcolor-input" class="form-control" placeholder="#d9e1ff">
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="modal-bgcolor-save">Apply</button>
            </div>
        </div>
    </div>
</div>


<!-- ==========================================================
     MODAL: GENERAL ADMISSION CAPACITY
     ========================================================== -->
<div class="modal fade" id="modal-ga">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5>General Admission Capacity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">Capacity</label>
                <input type="number" id="modal-ga-input" class="form-control" value="0">
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="modal-ga-save">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- ==========================================================
     MODAL: ROW GENERATOR
     ========================================================== -->
<div class="modal fade" id="modal-generate-rows">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-dark text-light">
                <h5>Generate Rows</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Rows</label>
                    <input type="number" id="gen-rows" class="form-control" value="5">
                </div>

                <div class="mb-3">
                    <label>Columns</label>
                    <input type="number" id="gen-cols" class="form-control" value="10">
                </div>

                <div class="mb-3">
                    <label>Direction</label>
                    <select id="gen-dir" class="form-select">
                        <option value="ltr">Left → Right</option>
                        <option value="rtl">Right → Left</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Seat Size</label>
                    <input type="number" id="gen-size" class="form-control" value="22">
                </div>

                <div class="mb-3">
                    <label>Gap Between Seats</label>
                    <input type="number" id="gen-gap" class="form-control" value="6">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btn-apply-row-gen">Generate</button>
            </div>

        </div>
    </div>
</div>
