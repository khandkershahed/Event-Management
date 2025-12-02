{{-- =========================================
        ADD TICKET TYPE MODAL
========================================= --}}
<div class="modal fade" id="addTicketTypeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addTicketTypeForm">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Ticket Type</h5>
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <x-metronic.label class="required">Ticket Name</x-metronic.label>
                            <x-metronic.input type="text" name="name" required/>
                            <small class="text-danger error-name"></small>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-3">
                            <x-metronic.label class="required">Price</x-metronic.label>
                            <x-metronic.input type="number" step="0.01" name="price" required/>
                            <small class="text-danger error-price"></small>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-3">
                            <x-metronic.label class="required">Quantity</x-metronic.label>
                            <x-metronic.input type="number" name="quantity" required/>
                            <small class="text-danger error-quantity"></small>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <x-metronic.label>Description</x-metronic.label>
                            <x-metronic.textarea name="description" rows="3"></x-metronic.textarea>
                        </div>

                        {{-- Sections --}}
                        <div class="col-12">
                            <x-metronic.label class="required">Assign to Sections</x-metronic.label>
                            <div id="addSectionsContainer" class="row g-2"></div>
                            <small class="text-danger error-section_ids"></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Save <i class="fa fa-check ms-1"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


{{-- =========================================
        EDIT TICKET TYPE MODAL
========================================= --}}
<div class="modal fade" id="editTicketTypeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editTicketTypeForm">
            @csrf
            <input type="hidden" id="edit_ticket_id">

            <div class="modal-content">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Edit Ticket Type</h5>
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">×</button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <x-metronic.label class="required">Ticket Name</x-metronic.label>
                            <x-metronic.input type="text" name="name" id="edit_name" required/>
                            <small class="text-danger error-name"></small>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-3">
                            <x-metronic.label class="required">Price</x-metronic.label>
                            <x-metronic.input type="number" step="0.01" name="price" id="edit_price" required/>
                            <small class="text-danger error-price"></small>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-3">
                            <x-metronic.label class="required">Quantity</x-metronic.label>
                            <x-metronic.input type="number" name="quantity" id="edit_quantity" required/>
                            <small class="text-danger error-quantity"></small>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <x-metronic.label>Description</x-metronic.label>
                            <x-metronic.textarea id="edit_description" name="description"></x-metronic.textarea>
                        </div>

                        {{-- Sections --}}
                        <div class="col-12">
                            <x-metronic.label class="required">Assign to Sections</x-metronic.label>
                            <div id="editSectionsContainer" class="row g-2"></div>
                            <small class="text-danger error-section_ids"></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        Update <i class="fa fa-save ms-1"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


{{-- =========================================
        DELETE TICKET TYPE MODAL
========================================= --}}
<div class="modal fade" id="deleteTicketTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Ticket Type?</h5>
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <p class="text-muted">Are you sure you want to delete this ticket type?</p>
                <input type="hidden" id="delete_ticket_id">
            </div>

            <div class="modal-footer text-end">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteTicketType">
                    Delete <i class="fa fa-trash ms-1"></i>
                </button>
            </div>

        </div>
    </div>
</div>
