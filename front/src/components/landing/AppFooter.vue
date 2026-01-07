<script setup>
import { ref, reactive } from 'vue'

const socialLinks = ref([
  { name: 'facebook', icon: 'fa-brands fa-facebook-f', url: '#' },
  { name: 'twitter', icon: 'fa-brands fa-twitter', url: '#' },
  { name: 'instagram', icon: 'fa-brands fa-instagram', url: '#' },
  { name: 'linkedin', icon: 'fa-brands fa-linkedin-in', url: '#' }
])

const footerLinks = reactive({
  product: [
    { text: 'Fonctionnalités', url: '#features' },
    { text: 'Tarifs', url: '#' },
    { text: 'Témoignages', url: '#' },
    { text: 'FAQ', url: '#faq' }
  ],
  company: [
    { text: 'À propos', url: '#' },
    { text: 'Blog', url: '#' },
    { text: 'Carrières', url: '#' },
    { text: 'Contact', url: '#contact' }
  ],
  legal: [
    { text: 'Confidentialité', url: '#' },
    { text: 'Conditions d\'utilisation', url: '#' },
    { text: 'Cookies', url: '#' },
    { text: 'Mentions légales', url: '#' }
  ]
})
</script>

<template>
  <footer class="bg-[#333333] text-white py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 mb-8 md:mb-12">
        <div class="sm:col-span-2 md:col-span-1">
          <h2 class="text-2xl md:text-3xl font-['Outfit'] font-bold mb-3 md:mb-4">homeez</h2>
          <p class="text-gray-400 mb-4 md:mb-6 text-sm md:text-base">
            Simplifiez la gestion de votre maison avec notre plateforme tout-en-un.
          </p>
          <div class="flex space-x-3 md:space-x-4">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.url"
              class="w-9 h-9 md:w-10 md:h-10 bg-[#474747] bg-opacity-10 rounded-lg flex items-center justify-center hover:bg-green_pastel transition"
            >
              <i :class="social.icon" class="text-white text-sm"></i>
            </a>
          </div>
        </div>

        <div>
          <h4 class="text-base md:text-lg font-['Outfit'] font-semibold mb-3 md:mb-4">Produit</h4>
          <ul class="space-y-2 md:space-y-3">
            <li v-for="link in footerLinks.product" :key="link.text">
              <a :href="link.url" class="text-gray-400 hover:text-green_pastel transition text-sm md:text-base">
                {{ link.text }}
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="text-base md:text-lg font-['Outfit'] font-semibold mb-3 md:mb-4">Entreprise</h4>
          <ul class="space-y-2 md:space-y-3">
            <li v-for="link in footerLinks.company" :key="link.text">
              <a :href="link.url" class="text-gray-400 hover:text-green_pastel transition text-sm md:text-base">
                {{ link.text }}
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="text-base md:text-lg font-['Outfit'] font-semibold mb-3 md:mb-4">Légal</h4>
          <ul class="space-y-2 md:space-y-3">
            <li v-for="link in footerLinks.legal" :key="link.text">
              <a :href="link.url" class="text-gray-400 hover:text-green_pastel transition text-sm md:text-base">
                {{ link.text }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-700 pt-6 md:pt-8 text-center">
        <p class="text-gray-400 text-sm md:text-base">&copy; 2025 homeez. Tous droits réservés.</p>
      </div>
    </div>
  </footer>
</template>