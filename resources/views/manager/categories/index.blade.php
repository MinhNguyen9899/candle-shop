<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Quản lý danh mục sản phẩm
            </h2>

            <a href="{{ route('manager.categories.create') }}"
                style="background-color: #4F46E5; color: #FFFFFF;"
                class="inline-flex items-center px-5 py-2.5 rounded-lg font-semibold shadow-sm hover:opacity-90 transition">

                <span class="mr-2 text-lg">+</span>
                Thêm danh mục
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tìm kiếm và lọc --}}
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form method="GET" action="{{ route('manager.categories.index') }}"
                    class="flex flex-wrap gap-3">

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Nhập tên danh mục..."
                        class="border-gray-300 rounded-md shadow-sm flex-1 min-w-48">

                    <select name="status"
                        class="border-gray-300 rounded-md shadow-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Đang hiển thị
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Đang ẩn
                        </option>
                    </select>

                    <button type="submit"
                        class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                        Tìm kiếm
                    </button>

                    <a href="{{ route('manager.categories.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Làm mới
                    </a>
                </form>
            </div>

            {{-- Bảng danh mục --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    STT
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Tên danh mục
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Slug
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Số sản phẩm
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Trạng thái
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $category->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $category->slug }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $category->products_count }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        @if ($category->status === 'active')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                                Đang hiển thị
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full">
                                                Đang ẩn
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-center">
                                        <div class="flex items-center justify-center gap-3">

                                            <a href="{{ route('manager.categories.edit', $category) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Sửa
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('manager.categories.toggle-status', $category) }}"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái danh mục này?');">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">
                                                    {{ $category->status === 'active' ? 'Ẩn' : 'Hiện' }}
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Chưa có danh mục sản phẩm nào.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $categories->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>