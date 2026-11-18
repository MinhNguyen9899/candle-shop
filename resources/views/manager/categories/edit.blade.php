<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chỉnh sửa danh mục
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-lg shadow-sm">

                <form method="POST"
                    action="{{ route('manager.categories.update', $category) }}">

                    @csrf
                    @method('PUT')

                    {{-- Tên danh mục --}}
                    <div class="mb-5">
                        <label for="name" class="block font-medium text-sm text-gray-700 mb-2">
                            Tên danh mục <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm">

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mô tả --}}
                    <div class="mb-5">
                        <label for="description" class="block font-medium text-sm text-gray-700 mb-2">
                            Mô tả
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Trạng thái hiện tại --}}
                    <div class="mb-5">
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            Trạng thái hiện tại
                        </p>

                        @if ($category->status === 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                Đang hiển thị
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                                Đang ẩn
                            </span>
                        @endif
                    </div>

                    {{-- Nút thao tác --}}
                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            style="background-color: #4F46E5; color: #FFFFFF;"
                            class="px-5 py-2.5 rounded-lg font-semibold shadow-sm hover:opacity-90 transition">
                            Cập nhật
                        </button>

                        <a
                            href="{{ route('manager.categories.index') }}"
                            style="background-color: #E5E7EB; color: #1F2937;"
                            class="inline-flex px-5 py-2.5 rounded-lg font-semibold hover:opacity-90 transition">
                            Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>