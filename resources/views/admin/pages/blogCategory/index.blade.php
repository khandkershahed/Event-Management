<x-admin-app-layout :title="'Blog Category List'">
    <div class="card">
        <div class="card-header bg-dark d-flex justify-content-between align-items-center">
            <h1 class="mb-0 text-white">Manage Your Blog Category</h1>
            <a href="javascript:void(0)" class="btn btn-light-primary rounded-2" data-bs-toggle="modal"
                data-bs-target="#AddModal">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                            fill="currentColor" />
                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1"
                            transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1"
                            fill="currentColor" />
                    </svg>
                </span>
                <span class="pt-2">Add Category</span>
            </a>
        </div>
        <div class="card-body py-0">
            <table class="table my-datatable table-striped table-row-bordered gy-5 gs-7">
                <thead class="">
                    <tr class="fw-semibold fs-6 text-gray-800">
                        <th width="8%">SL No.</th>
                        <th width="12%">Image</th>
                        <th width="28%">Name</th>
                        <th width="27%">Meta Title</th>
                        <th width="15%">Status</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blogCategories as $blogcategory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><img class="w-65px" src="{{ asset('storage/'.$blogcategory->image ) }}" alt=""></td>
                            <td>{{ $blogcategory->name }}</td>
                            <td>{{ $blogcategory->meta_title }}</td>
                            <td>
                                <span class="badge {{ $blogcategory->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $blogcategory->status == 'active' ? 'Active' : 'InActive' }}</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                    data-bs-toggle="modal" data-bs-target="#EditModal-{{ $blogcategory->id }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="{{ route('admin.blog-category.destroy', $blogcategory->id) }}"
                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 delete">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- Blog Category Create Modal --}}
    <div class="modal fade" tabindex="-1" id="AddModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-3 bg-primary">
                    <h3 class="modal-title text-white">Create Blog Category</h3>
                    <button type="button" class="btn btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="kt_docs_formvalidation_text" class="form"
                        action="{{ route('admin.blog-category.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="fv-row mb-5">
                            <x-metronic.label class="required fw-semibold fs-6 mb-2">Name</x-metronic.label>
                            <x-metronic.input type="text" name="name" class="form-control mb-3 mb-lg-0"
                                placeholder="Set Blog Category Name" :value="old('name')" />
                        </div>
                        <div class="fv-row mb-5">
                            <x-metronic.label class="fw-semibold fs-6 mb-2">Meta Title</x-metronic.label>
                            <x-metronic.input type="text" name="meta_title" class="form-control mb-3 mb-lg-0"
                                placeholder="Set Blog Meta Title" :value="old('meta_title')" />
                        </div>
                        <div class="fv-row mb-5">
                            <x-metronic.label for="image" class="col-form-label fw-bold fs-6 ">{{ __('Blog Category Image/icon') }}
                            </x-metronic.label>
                            <x-metronic.file-input id="image" name="image" class="form-control mb-3 mb-lg-0"
                                :value="old('image')"></x-metronic.file-input>
                        </div>
                        <div class="fv-row mb-5">
                            <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                                {{ __('Select a Status ') }}</x-metronic.label>
                            <x-metronic.select-option id="status" name="status" data-hide-search="true"
                                data-placeholder="Select an option">
                                <option></option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </x-metronic.select-option>
                        </div>
                        <div class="fv-row mb-5">
                            <x-metronic.label class="fw-semibold fs-6 mb-2">Description</x-metronic.label>
                            <x-metronic.textarea class="form-control" placeholder="Set The Description"
                                name="description" id="floatingTextarea2" style="height: 100px"
                                :value="old('description')"></x-metronic.textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{-- <button id="kt_docs_formvalidation_text_submit" type="submit" class="btn btn-primary">
                                <span class="indicator-label">Create Category</span>
                                <span class="indicator-progress">Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button> --}}
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($blogCategories as $category)
        <div class="modal fade" tabindex="-1" id="EditModal-{{ $category->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header py-3 bg-light-primary">
                        <h3 class="modal-title">Update Blog Category</h3>
                        <button type="button" class="btn btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fa-solid fa-xmark fs-1"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="kt_docs_formvalidation_text" class="form"
                            action="{{ route('admin.blog-category.update',$category->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="fv-row mb-5">
                                <x-metronic.label class="required fw-semibold fs-6 mb-2">Name</x-metronic.label>
                                <x-metronic.input type="text" name="name" class="form-control mb-3 mb-lg-0"
                                    placeholder="Set Name" :value="old('name',$category->name)" />
                            </div>
                            <div class="fv-row mb-5">
                                <x-metronic.label class="fw-semibold fs-6 mb-2">Meta Title</x-metronic.label>
                                <x-metronic.input type="text" name="meta_title" class="form-control mb-3 mb-lg-0"
                                    placeholder="Set Meta Title" :value="old('meta_title',$category->meta_title)" />
                            </div>
                            <div class="fv-row mb-5">
                                <x-metronic.label for="image" class="col-form-label fw-bold fs-6 ">{{ __('Blog Image') }}
                                </x-metronic.label>
                                <x-metronic.file-input id="image" name="image" class="form-control mb-3 mb-lg-0"
                                    :value="old('image')" :source="asset('storage/'.$category->image)"></x-metronic.file-input>
                            </div>
                            <div class="fv-row mb-5">
                                <x-metronic.label for="status" class="col-form-label required fw-bold fs-6">
                                    {{ __('Select a Status ') }}</x-metronic.label>
                                <x-metronic.select-option id="status" name="status" data-hide-search="true"
                                    data-placeholder="Select an option">
                                    <option></option>
                                    <option value="active" @selected($category->status == "active") >Active</option>
                                    <option value="inactive" @selected($category->status == "inactive") >Inactive</option>
                                </x-metronic.select-option>
                            </div>
                            <div class="fv-row mb-5">
                                <x-metronic.label class="fw-semibold fs-6 mb-2">Description</x-metronic.label>
                                <x-metronic.textarea class="form-control" placeholder="Set The Description"
                                    name="description" id="floatingTextarea2" style="height: 100px"
                                    :value="old('description')">{!! $category->description !!}</x-metronic.textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Blog Category Create Modal End --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('kt_docs_formvalidation_text');
                const submitButton = document.getElementById('kt_docs_formvalidation_text_submit');

                var validator = FormValidation.formValidation(
                    form, {
                        fields: {
                            'name': {
                                validators: {
                                    notEmpty: {
                                        message: 'This Field Required'
                                    }
                                }
                            },
                            'status': {
                                validators: {
                                    notEmpty: {
                                        message: 'This Field Required'
                                    }
                                }
                            },
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '',
                                eleValidClass: ''
                            })
                        }
                    }
                );

                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (validator) {
                        validator.validate().then(function(status) {
                            if (status == 'Valid') {
                                submitButton.setAttribute('data-kt-indicator', 'on');
                                submitButton.disabled = true;

                                const formData = new FormData(form);
                                fetch(form.action, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content')
                                        },
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        submitButton.removeAttribute('data-kt-indicator');
                                        submitButton.disabled = false;
                                        if (data.success) {
                                            Swal.fire({
                                                text: "Form has been successfully submitted!",
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-primary"
                                                }
                                            }).then(function() {
                                                window.location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                text: data.message ||
                                                    "An error occurred. Please try again.",
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-primary"
                                                }
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        submitButton.removeAttribute('data-kt-indicator');
                                        submitButton.disabled = false;
                                        Swal.fire({
                                            text: "An error occurred. Please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
