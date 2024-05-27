<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="xl:flex xl:sticky top-3 m-3">
            <div class="w-full sm:w-auto grid grid-cols-2 justify-between xl:grid-rows-3 xl:grid-cols-1 gap-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center row-span-2">
                    <ApplicationMark
                        class="block w-36 h-36 cursor-pointer"
                        @click="() => router.visit(route('client.main'))"
                    />
                </div>

                <!-- Navigation Toggle Button -->
                <div class="flex items-center justify-end xl:justify-center self-start">
                    <button
                        :class="{
                            'text-white-500': showingNavigationDropdown,
                            'text-gray-500': !showingNavigationDropdown,
                            'bg-red-500': showingNavigationDropdown,
                            'dark:bg-gray-900': !showingNavigationDropdown,
                        }"
                        class="p-2 inline-flex items-center justify-center rounded-md focus:outline-none transition duration-150 ease-in-out"
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                    >
                        <svg
                            class="h-12 w-12"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                d="M4 6h16M7 12h16M4 18h16"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                            <path
                                :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                d="M12 18L18 6M6 6l12 12"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div :class="{'relative': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}">
                    <div class="absolute z-50 w-full pb-1 border-t border-gray-200 dark:border-gray-600 dark:bg-[#121212]">
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                v-if="hasUser"
                                :active="route().current('profile.show')"
                                :href="route('profile.show')"
                            >
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="hasUser"
                                as="button"
                                @click="logout"
                            >
                                Log Out
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-else
                                as="button"
                                @click="login"
                            >
                                Log In
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
const form = useForm({ });
const page = usePage();

const user = ref({ });
const hasUser = computed(() => user.value?.uuid);

onMounted(() => {
    user.value = page.props.auth.user;
})

const login = () => {
    router.visit(route('login'));
};

const logout = () => {
    form.transform(() => ({ }))
        .post(route('logout'), {
            onFinish: () => (showingNavigationDropdown.value = false)
            && router.reload(),
        });
};

</script>

<style scoped>

</style>
