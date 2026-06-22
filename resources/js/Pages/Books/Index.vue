<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// `defineProps` declares the data this page receives from the server.
// `books` is the (already-filtered) list; `available` is the current filter
// state the controller applied, so we can show the checkbox in the right state.
const props = defineProps({
    books: {
        type: Array,
        required: true,
    },
    available: {
        type: Boolean,
        default: false,
    },
});

// Seed the checkbox from the server's filter state. This is why reloading
// /books?available=1 shows the box already ticked instead of resetting.
const showAvailableOnly = ref(props.available);

// `watch` runs a callback whenever a reactive source changes. When the toggle
// flips, we ask the server for the matching list via Inertia's `router.get` --
// a partial visit, not a full browser reload. We send `available` only when the
// box is on; `preserveScroll` keeps the page position, and `replace` avoids
// piling a new history entry onto the back button for every toggle.
watch(showAvailableOnly, (isOn) => {
    router.get(
        route('books.index'),
        isOn ? { available: 1 } : {},
        { preserveState: true, preserveScroll: true, replace: true },
    );
});
</script>

<template>
    <!-- <Head> sets the browser tab title without us touching the HTML <head> by hand. -->
    <Head title="Catalog" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Catalog
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!--
                    Filter toggle. `v-model` two-way binds the checkbox to our
                    `showAvailableOnly` ref; the watcher above turns that change
                    into a server request, so the backend does the actual filtering.
                -->
                <div class="mb-6 flex items-center justify-end">
                    <label
                        class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700"
                    >
                        <input
                            type="checkbox"
                            v-model="showAvailableOnly"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        />
                        Show available only
                    </label>
                </div>

                <!--
                    Responsive grid: 1 column on mobile, scaling up to 4 on wide
                    screens. `v-for` renders one card per book, and Vue requires a
                    unique `:key` (the book id) so it can track each item efficiently
                    when the list changes.
                -->
                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <!--
                        `Link` is Inertia's drop-in replacement for an <a> tag: it
                        navigates between pages without a full browser reload.
                        `route('books.show', book.id)` builds the URL from the named
                        route, filling the {book} segment with this book's id.
                    -->
                    <Link
                        v-for="book in books"
                        :key="book.id"
                        :href="route('books.show', book.id)"
                        class="flex flex-col justify-between rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md"
                    >
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ book.title }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ book.author_name }}
                            </p>
                        </div>

                        <!--
                            Availability badge. The `:class` binding chooses colours
                            from a simple ternary based on whether copies remain.
                        -->
                        <span
                            class="mt-4 inline-block self-start rounded-full px-2 py-0.5 text-xs font-semibold"
                            :class="
                                book.available_copies > 0
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700'
                            "
                        >
                            {{
                                book.available_copies > 0
                                    ? `${book.available_copies} of ${book.total_copies} available`
                                    : 'Unavailable'
                            }}
                        </span>
                    </Link>
                </div>

                <!--
                    Empty state. With the filter on, an empty list means nothing
                    is currently available; otherwise the catalog is truly empty.
                -->
                <p
                    v-if="books.length === 0"
                    class="mt-6 text-center text-gray-500"
                >
                    {{
                        showAvailableOnly
                            ? 'No books are currently available.'
                            : 'No books in the catalog yet.'
                    }}
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
