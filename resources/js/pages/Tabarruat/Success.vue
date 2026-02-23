<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Get submitted data from flash session
const submittedData = computed(() => {
    return (page.props as any).flash?.data || null
})
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-emerald-50 to-white py-8 px-4 dark:from-gray-900 dark:to-gray-900 sm:px-6">
        <div class="max-w-2xl mx-auto">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-emerald-100 overflow-hidden dark:bg-gray-800 dark:border-emerald-900">
                <!-- Success Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Alhamdulillah!</h1>
                    <p class="text-emerald-100">Permohonan tabarruat berjaya dihantar</p>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Thank You Message -->
                    <div class="text-center mb-8">
                        <p class="text-gray-600 mb-2 dark:text-gray-300">
                            Terima kasih kerana memberikan sumbangan tabarruat anda.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            Semoga sumbangan anda memberikan manfaat kepada arwah dan mendapat ganjaran pahala dari Allah SWT.
                        </p>
                    </div>

                    <!-- Submitted Details -->
                    <div v-if="submittedData" class="bg-emerald-50 rounded-xl p-6 mb-8 border border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-700">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-4 text-center dark:text-emerald-300">Butiran Permohonan</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-start py-2 border-b border-emerald-200 dark:border-emerald-700">
                                <span class="text-gray-600 dark:text-gray-300">Nama Pemohon</span>
                                <span class="font-semibold text-gray-800 text-right dark:text-gray-100">{{ submittedData.nama_pemohon }}</span>
                            </div>

                            <div class="flex justify-between items-start py-2 border-b border-emerald-200 dark:border-emerald-700">
                                <span class="text-gray-600 dark:text-gray-300">PASTI</span>
                                <span class="font-semibold text-gray-800 text-right dark:text-gray-100">{{ submittedData.pasti }}</span>
                            </div>

                            <div class="py-2">
                                <span class="text-gray-600 block mb-2 dark:text-gray-300">Nama Arwah</span>
                                <ul class="space-y-1">
                                    <li v-for="(arwah, index) in submittedData.nama_arwah" :key="index" class="font-semibold text-gray-800 text-right dark:text-gray-100">
                                        {{ index + 1 }}. {{ arwah }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Doa -->
                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-6 mb-8 border border-amber-200 text-center dark:from-amber-900/30 dark:to-amber-900/30 dark:border-amber-700">
                        <p class="text-amber-800 font-medium mb-2 dark:text-amber-300">Doa Untuk Arwah</p>
                        <p class="text-amber-700 text-sm leading-relaxed dark:text-amber-200">
                            "Allahummaghfir li hayyina mayyitina wa shahidina wa ghaibina wa da'ifina wa 'ala ma arsaltana bihi amrina wa aqsimna min rizqika 'ala ahyina wa amwatina. Allahummaghfir lahum warhamhum wa 'afi him wa'fu 'anhum."
                        </p>
                        <p class="text-amber-600 text-xs mt-3 dark:text-amber-400">
                            "Ya Allah, ampunilah orang hidup dan orang mati kami, yang hadir dan yang tidak hadir, yang lemah dan kuat di kalangan kami. Yang Engkau jadikan urusan kami bergantung kepadanya. Dan bahagikan rezeki Engkau kepada orang hidup dan mati kami. Ya Allah, ampunilah mereka, rahmatilah mereka, sejahterakan mereka dan maafkanlah mereka."
                        </p>
                    </div>

                    <!-- Action Button -->
                    <a
                        href="/"
                        class="block w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 text-center dark:bg-emerald-600 dark:hover:bg-emerald-500"
                    >
                        Mohon Lagi
                    </a>
                </div>

                <!-- Footer Decoration -->
                <div class="bg-emerald-50 px-6 py-4 text-center border-t border-emerald-100 dark:bg-emerald-900/30 dark:border-emerald-800">
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">
                        "Dan apa sahaja harta yang nafkahkan, maka (faedahnya dan kegunaannya) kembali kepada kamu sendiri, dan kamu jangan membelanjakan harta melainkan untuk mencari keredhaan Allah."
                    </p>
                    <p class="text-xs text-emerald-600 mt-2 dark:text-emerald-400">— Surah Al-Baqarah: 272</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-gray-500 dark:text-gray-400">
                <p>&copy; {{ new Date().getFullYear() }} Tabarruat PASTI</p>
            </div>
        </div>
    </div>
</template>
