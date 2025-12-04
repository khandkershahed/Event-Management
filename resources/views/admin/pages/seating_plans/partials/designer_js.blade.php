<div class="modal fade" id="createSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Create New Section</h5>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Section Name</label>
                    <input type="text" class="form-control" id="new_section_name" placeholder="VIP, Balcony, Block A">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Type</label>
                    <select class="form-select" id="new_section_type">
                        <option value="seat">Seat Section</option>
                        <option value="table">Table</option>
                        <option value="general_admission">General Admission</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Capacity (Optional)</label>
                    <input type="number" id="new_section_capacity" class="form-control" placeholder="0">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button id="createSectionBtn" class="btn btn-primary">Create Section</button>
            </div>

        </div>
    </div>
</div>
