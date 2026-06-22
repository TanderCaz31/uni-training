<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// This page receives a single `book` object from BookController@show.
defineProps({
    book: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <!-- Bind the tab title to the book's title with `:title` (a dynamic attribute). -->
    <Head :title="book.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ book.title }}
                </h2>
                <!-- Inertia Link back to the catalogue index. -->
                <Link
                    :href="route('books.index')"
                    class="text-sm text-gray-500 hover:text-gray-700"
                >
                    &larr; Back to catalog
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-6 p-6 text-gray-900">
                        <div>
                            <h3 class="text-2xl font-bold">{{ book.title }}</h3>
                            <p class="mt-1 text-gray-600">
                                by {{ book.author_name }}
                            </p>
                        </div>

                        <!-- Availability badge, same logic as the catalogue card. -->
                        <span
                            class="inline-block rounded-full px-3 py-1 text-sm font-semibold"
                            :class="
                                book.available_copies > 0
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700'
                            "
                        >
                            {{
                                book.available_copies > 0
                                    ? `${book.available_copies} of ${book.total_copies} copies available`
                                    : 'Currently unavailable'
                            }}
                        </span>

                        <!--
                            `v-if` only renders this block when a description exists.
                            The column is nullable, so some books may not have one.
                        -->
                        <p
                            v-if="book.description"
                            class="leading-relaxed text-gray-700"
                        >
                            {{ book.description }}
                        </p>

                        <!-- A small definition list for the remaining metadata. -->
                        <dl
                            class="grid grid-cols-1 gap-4 border-t border-gray-100 pt-6 sm:grid-cols-2"
                        >
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    ISBN
                                </dt>
                                <dd class="mt-1 text-gray-900">{{ book.isbn }}</dd>
                            </div>
                            <div v-if="book.published_year">
                                <dt class="text-sm font-medium text-gray-500">
                                    Published
                                </dt>
                                <dd class="mt-1 text-gray-900">
                                    {{ book.published_year }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
