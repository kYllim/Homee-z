<script setup>
import { ref } from 'vue'
import BenefitItem from './BenefitItem.vue'

const benefits = ref([
  {
    id: 1,
    icon: 'fa-solid fa-clock',
    title: 'Gagnez du temps',
    description: 'Automatisez vos routines et consacrez plus de temps à ce qui compte vraiment pour vous et votre famille.'
  },
  {
    id: 2,
    icon: 'fa-solid fa-users',
    title: 'Collaboration familiale',
    description: 'Impliquez tous les membres de la famille dans la gestion du foyer avec des outils de partage intuitifs.'
  },
  {
    id: 3,
    icon: 'fa-solid fa-shield-halved',
    title: 'Sécurité et confidentialité',
    description: 'Vos données sont protégées avec les plus hauts standards de sécurité et restent strictement privées.'
  },
  {
    id: 4,
    icon: 'fa-solid fa-mobile-screen',
    title: 'Accessible partout',
    description: 'Accédez à homeez depuis n\'importe quel appareil, à tout moment et en tout lieu.'
  }
])
</script>

<template>
  <section id="benefits" class="py-12 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <div class="text-center mb-8 md:mb-16">
        <h3 class="text-2xl sm:text-3xl md:text-4xl font-['Outfit'] font-bold text-[#333333] mb-3 md:mb-4">
          Pourquoi choisir homeez ?
        </h3>
        <p class="text-base md:text-lg text-lighBlue">
          Découvrez les avantages d'une gestion domestique moderne
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
        <div class="space-y-6 md:space-y-8">
          <BenefitItem
            v-for="benefit in benefits"
            :key="benefit.id"
            :icon="benefit.icon"
            :title="benefit.title"
            :description="benefit.description"
          />
        </div>
        <div class="flex justify-center mt-6 md:mt-0 order-first md:order-last">
          <div class="w-full h-64 md:h-[500px] bg-linear-to-br from-green_pastel to-lighBlue rounded-2xl md:rounded-3xl shadow-xl flex items-center justify-center">
            <i class="fa-solid fa-home text-white text-6xl md:text-9xl opacity-30"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>