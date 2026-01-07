<script setup>
import { ref, reactive } from 'vue'

const contactInfo = ref([
  {
    id: 1,
    icon: 'fa-solid fa-envelope',
    title: 'Email',
    value: 'contact@homeez.fr'
  },
  {
    id: 2,
    icon: 'fa-solid fa-phone',
    title: 'Téléphone',
    value: '+33 1 23 45 67 89'
  },
  {
    id: 3,
    icon: 'fa-solid fa-location-dot',
    title: 'Adresse',
    value: '123 Rue de la Paix, 75002 Paris, France'
  }
])

const contactForm = reactive({
  name: '',
  email: '',
  message: ''
})

const submitContactForm = () => {
  console.log('Formulaire soumis:', contactForm)
  alert('Message envoyé avec succès !')
  contactForm.name = ''
  contactForm.email = ''
  contactForm.message = ''
}
</script>

<template>
  <section id="contact" class="py-12 md:py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4 md:px-6">
      <div class="text-center mb-8 md:mb-16">
        <h3 class="text-2xl sm:text-3xl md:text-4xl font-['Outfit'] font-bold text-[#333333] mb-3 md:mb-4">
          Contactez-nous
        </h3>
        <p class="text-base md:text-lg text-lighBlue">
          Une question ? Une suggestion ? Nous sommes là pour vous écouter
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
        <div class="space-y-6 md:space-y-8">
          <div v-for="info in contactInfo" :key="info.id" class="flex items-start space-x-3 md:space-x-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-green_pastel bg-opacity-10 rounded-lg flex items-center justify-center shrink-0">
              <i :class="info.icon" class="text-green_pastel text-lg md:text-xl"></i>
            </div>
            <div>
              <h4 class="text-base md:text-lg font-['Outfit'] font-semibold text-[#333333] mb-1">
                {{ info.title }}
              </h4>
              <p class="text-sm md:text-base text-lighBlue">
                {{ info.value }}
              </p>
            </div>
          </div>
        </div>
        <div class="bg-[#FAFAFA] rounded-xl md:rounded-2xl p-6 md:p-8">
          <div class="space-y-4 md:space-y-6">
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Nom complet</label>
              <input
                v-model="contactForm.name"
                type="text"
                class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 focus:border-green_pastel focus:ring-2 focus:ring-green_pastel focus:ring-opacity-20 outline-none transition text-sm md:text-base"
                placeholder="Jean Dupont"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Email</label>
              <input
                v-model="contactForm.email"
                type="email"
                class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 focus:border-green_pastel focus:ring-2 focus:ring-green_pastel focus:ring-opacity-20 outline-none transition text-sm md:text-base"
                placeholder="jean.dupont@email.fr"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Message</label>
              <textarea
                v-model="contactForm.message"
                rows="4"
                class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 focus:border-green_pastel focus:ring-2 focus:ring-green_pastel focus:ring-opacity-20 outline-none transition resize-none text-sm md:text-base"
                placeholder="Votre message..."
              ></textarea>
            </div>
            <button
              @click="submitContactForm"
              type="button"
              class="w-full px-6 py-3 md:py-3.5 bg-green_pastel text-white rounded-lg hover:bg-opacity-90 transition font-medium text-sm md:text-base"
            >
              Envoyer le message
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>