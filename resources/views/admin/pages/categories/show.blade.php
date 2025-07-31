<x-admin-app-layout :title="'Category Show'">
    <div class="py-5">
        <div class="container mx-auto px-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="font-weight-bold mb-4">Category Name: <p class="text-muted fs-4">
                            {{ $category->name }}</p>
                    </h2>

                    <div class="mb-4">
                        <p><strong>ID:</strong> {{ $category->id }}</p>
                        <p><strong>Name:</strong> {{ $category->name }}</p>
                        <p><strong>Slug:</strong> {{ $category->slug }}</p>
                        <p><strong>Status:</strong> {{ $category->status == 1 ? 'Active' : 'Inactive' }}</p>
                        <p><strong>Created At:</strong> {{ $category->created_at->format('F j, Y, g:i a') }}</p>
                        <p><strong>Last Updated:</strong> {{ $category->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    @if ($category->parent)
                        <div class="mb-4">
                            <p><strong>Parent Category:</strong> {{ $category->parent->name }}</p>
                        </div>
                    @endif

                    @if ($category->children->isNotEmpty())
                        <div class="mb-4">
                            <p><strong>Child Categories ({{ $category->children->count() }}):</strong></p>
                            <ul class="ps-3">
                                @foreach ($category->children as $child)
                                    <li>
                                        <strong>Child:</strong> {{ $child->name }}
                                        @if ($child->children->isNotEmpty())
                                            <ul class="ps-3">
                                                @foreach ($child->children as $grandchild)
                                                    <li><strong>Grandchild:</strong> {{ $grandchild->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.categories.index') }}" class="text-primary">Back to list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-app-layout>
