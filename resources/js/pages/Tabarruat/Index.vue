<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();

// Form state
const form = ref({
    nama_pemohon: '',
    pasti: '',
    nama_arwah: [''] as string[],
    sudah_sumbangan: false,
});

// PASTI options
const pastiOptions = ref<string[]>([]);

// Current step
const currentStep = ref<'form' | 'qr' | 'confirmation'>('form');

// Loading state
const loading = ref(false);

// QR code image path - replace this with your actual QR code image
const qrCodeImage = ref('/images/qr-sumbangan.png');

// Errors
const errors = ref<Record<string, string>>({});

// Fetch PASTI options on mount
onMounted(async () => {
    try {
        const response = await fetch('/api/pasti-options');
        const data = await response.json();
        pastiOptions.value = data.pasti;
    } catch (error) {
        console.error('Failed to fetch PASTI options:', error);
    }
});

// Add new deceased name field
const addArwahField = () => {
    form.value.nama_arwah.push('');
};

// Remove deceased name field
const removeArwahField = (index: number) => {
    if (form.value.nama_arwah.length > 1) {
        form.value.nama_arwah.splice(index, 1);
    }
};

// Update deceased name
const updateArwahName = (index: number, value: string) => {
    form.value.nama_arwah[index] = value;
};

// Proceed to next step
const proceedToNext = () => {
    // Validate form
    const newErrors: Record<string, string> = {};

    if (!form.value.nama_pemohon.trim()) {
        newErrors.nama_pemohon = 'Sila masukkan nama pemohon';
    }

    if (!form.value.pasti) {
        newErrors.pasti = 'Sila pilih PASTI';
    }

    const validArwah = form.value.nama_arwah.filter((n) => n.trim());
    if (validArwah.length === 0) {
        newErrors.nama_arwah =
            'Sila masukkan sekurang-kurangnya satu nama arwah';
    }

    errors.value = newErrors;

    if (Object.keys(newErrors).length === 0) {
        if (form.value.sudah_sumbangan) {
            currentStep.value = 'confirmation';
        } else {
            currentStep.value = 'qr';
        }
    }
};

// Submit form
const submitForm = async () => {
    loading.value = true;
    errors.value = {};

    try {
        await router.post('/tabarruat', {
            nama_pemohon: form.value.nama_pemohon,
            pasti: form.value.pasti,
            nama_arwah: form.value.nama_arwah.filter((n) => n.trim()),
            sudah_sumbangan: form.value.sudah_sumbangan,
        });
    } catch (error: any) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
};

// Go back to form
const goBack = () => {
    currentStep.value = 'form';
};

// Check for success message
const successMessage = computed(() => {
    return (page.props as any).flash?.success || '';
});
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-b from-emerald-50 to-white px-4 py-8 sm:px-6"
    >
        <div class="mx-auto max-w-2xl">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1
                    class="mb-2 text-3xl font-bold text-emerald-800 sm:text-4xl"
                >
                    Borang Tabarruat
                </h1>
            </div>

            <!-- Success Message -->
            <div
                v-if="successMessage"
                class="mb-6 rounded-lg border border-emerald-300 bg-emerald-100 p-4 text-center text-emerald-800"
            >
                {{ successMessage }}
            </div>

            <!-- Main Card -->
            <div
                class="overflow-hidden rounded-2xl border border-emerald-100 bg-white shadow-xl"
            >
                <!-- Form Step -->
                <div v-if="currentStep === 'form'" class="p-6 sm:p-8">
                    <div class="space-y-6">
                        <!-- Nama Pemohon -->
                        <div>
                            <label
                                for="nama_pemohon"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Nama Pemohon <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="nama_pemohon"
                                v-model="form.nama_pemohon"
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Masukkan nama penuh"
                                :class="{
                                    'border-red-500': errors.nama_pemohon,
                                }"
                            />
                            <p
                                v-if="errors.nama_pemohon"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.nama_pemohon }}
                            </p>
                        </div>

                        <!-- PASTI -->
                        <div>
                            <label
                                for="pasti"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                PASTI <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="pasti"
                                v-model="form.pasti"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                                :class="{ 'border-red-500': errors.pasti }"
                            >
                                <option value="">Pilih PASTI</option>
                                <option
                                    v-for="pasti in pastiOptions"
                                    :key="pasti"
                                    :value="pasti"
                                >
                                    {{ pasti }}
                                </option>
                            </select>
                            <p
                                v-if="errors.pasti"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.pasti }}
                            </p>
                        </div>

                        <!-- Nama Arwah -->
                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Nama Arwah
                                    <span class="text-red-500">*</span>
                                </label>
                                <button
                                    @click="addArwahField"
                                    type="button"
                                    class="flex items-center gap-1 text-sm font-medium text-emerald-600 hover:text-emerald-700"
                                >
                                    <span class="text-lg">+</span> Tambah
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div
                                    v-for="(arwah, index) in form.nama_arwah"
                                    :key="index"
                                    class="flex gap-2"
                                >
                                    <input
                                        v-model="form.nama_arwah[index]"
                                        type="text"
                                        class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500"
                                        placeholder="Masukkan nama arwah"
                                    />
                                    <button
                                        v-if="form.nama_arwah.length > 1"
                                        @click="removeArwahField(index)"
                                        type="button"
                                        class="rounded-lg px-3 py-2 text-red-500 transition hover:bg-red-50"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p
                                v-if="errors.nama_arwah"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.nama_arwah }}
                            </p>
                        </div>

                        <!-- Sumbangan Checkbox -->
                        <div
                            class="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4"
                        >
                            <input
                                id="sudah_sumbangan"
                                v-model="form.sudah_sumbangan"
                                type="checkbox"
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                            />
                            <label
                                for="sudah_sumbangan"
                                class="cursor-pointer text-sm text-gray-700"
                            >
                                Saya sudah memberikan sumbangan
                            </label>
                        </div>

                        <!-- Next Button -->
                        <button
                            @click="proceedToNext"
                            type="button"
                            class="w-full transform rounded-lg bg-emerald-600 px-6 py-4 font-semibold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-xl"
                        >
                            Seterusnya
                        </button>
                    </div>
                </div>

                <!-- QR Code Step -->
                <div
                    v-else-if="currentStep === 'qr'"
                    class="p-6 text-center sm:p-8"
                >
                    <div class="mb-6">
                        <div
                            class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-amber-100"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-amber-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h2 class="mb-2 text-2xl font-bold text-gray-800">
                            Sila Buat Sumbangan
                        </h2>
                        <p class="text-gray-600">
                            Sila scan QR code di bawah untuk membuat sumbangan
                            terlebih dahulu
                        </p>
                    </div>

                    <!-- QR Code Placeholder -->
                    <div class="mb-6 flex justify-center">
                        <div
                            class="flex h-64 w-64 items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-100"
                        >
                            <!-- Replace this with your actual QR code image -->
                            <img
                                v-if="qrCodeImage"
                                :src="qrCodeImage"
                                alt="QR Code Sumbangan"
                                class="h-full w-full rounded-xl object-contain"
                                onerror="
                                    this.style.display = 'none';
                                    this.nextElementSibling.style.display =
                                        'flex';
                                "
                            />
                            <div class="hidden p-4 text-center text-gray-400">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mx-auto mb-2 h-16 w-16"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                                    />
                                </svg>
                                <p class="text-sm">
                                    Sediakan gambar QR kod di<br /><code
                                        class="rounded bg-gray-200 px-2 py-1 text-xs"
                                        >public/images/qr-sumbangan.png</code
                                    >
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800"
                    >
                        <p>
                            Setelah membuat sumbangan, sila tandakan checkbox
                            "Saya sudah memberikan sumbangan" dan teruskan.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="goBack"
                            type="button"
                            class="flex-1 rounded-lg bg-gray-100 px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-200"
                        >
                            Kembali
                        </button>
                        <button
                            @click="
                                form.sudah_sumbangan = true;
                                proceedToNext();
                            "
                            type="button"
                            class="flex-1 rounded-lg bg-emerald-600 px-6 py-3 font-medium text-white transition hover:bg-emerald-700"
                        >
                            Saya Dah Sumbang
                        </button>
                    </div>
                </div>

                <!-- Confirmation Step -->
                <div
                    v-else-if="currentStep === 'confirmation'"
                    class="p-6 sm:p-8"
                >
                    <div class="mb-6 text-center">
                        <div
                            class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-emerald-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h2 class="mb-2 text-2xl font-bold text-gray-800">
                            Sahkan Permohonan
                        </h2>
                        <p class="text-gray-600">
                            Sila semak maklumat berikut sebelum menghantar
                        </p>
                    </div>

                    <!-- Confirmation Details -->
                    <div class="mb-6 space-y-4">
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="mb-1 text-sm text-gray-500">
                                Nama Pemohon
                            </p>
                            <p class="font-semibold text-gray-800">
                                {{ form.nama_pemohon }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="mb-1 text-sm text-gray-500">PASTI</p>
                            <p class="font-semibold text-gray-800">
                                {{ form.pasti }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="mb-1 text-sm text-gray-500">Nama Arwah</p>
                            <ul class="space-y-1">
                                <li
                                    v-for="(
                                        arwah, index
                                    ) in form.nama_arwah.filter((n) =>
                                        n.trim(),
                                    )"
                                    :key="index"
                                    class="font-semibold text-gray-800"
                                >
                                    {{ index + 1 }}. {{ arwah }}
                                </li>
                            </ul>
                        </div>

                        <div
                            class="rounded-lg border border-emerald-200 bg-emerald-50 p-4"
                        >
                            <p class="mb-1 text-sm text-emerald-600">
                                Status Sumbangan
                            </p>
                            <p class="font-semibold text-emerald-700">
                                {{
                                    form.sudah_sumbangan
                                        ? 'Sudah memberikan sumbangan'
                                        : 'Belum memberikan sumbangan'
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button
                            @click="goBack"
                            type="button"
                            :disabled="loading"
                            class="flex-1 rounded-lg bg-gray-100 px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Kembali
                        </button>
                        <button
                            @click="submitForm"
                            :disabled="loading"
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-3 font-medium text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <svg
                                v-if="loading"
                                class="h-5 w-5 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            {{
                                loading ? 'Menghantar...' : 'Hantar Permohonan'
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>&copy; {{ new Date().getFullYear() }} Tabarruat PASTI</p>
            </div>
        </div>
    </div>
</template>
