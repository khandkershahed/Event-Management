<div class="modal fade" id="editSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_section_id">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Section Name</label>
                    <input type="text" class="form-control" id="edit_section_name">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Type</label>
                    <select class="form-select" id="edit_section_type">
                        <option value="seat">Seat Section</option>
                        <option value="table">Table</option>
                        <option value="general_admission">General Admission</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Capacity</label>
                    <input type="number" id="edit_section_capacity" class="form-control" placeholder="0">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Rotation (Degrees)</label>
                    <input type="number" id="edit_section_rotation" class="form-control" placeholder="0">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSectionChangesBtn">
                    Save Changes
                </button>
            </div>

        </div>
    </div>
</div>
