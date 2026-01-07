<script setup>
import { ref } from 'vue'
import FeatureCard from './FeatureCard.vue'

const features = ref([
  {
    id: 1,
    icon: 'fa-solid fa-calendar-check',
    title: 'Emploi du temps en temps réel',
    description: 'Synchronisez les activités de toute la famille et ne manquez plus jamais un événement important.'
  },
  {
    id: 2,
    icon: 'fa-solid fa-cart-shopping',
    title: 'Listes de courses partagées',
    description: 'Créez et partagez vos listes de courses avec toute la famille en temps réel.'
  },
  {
    id: 3,
    icon: 'fa-solid fa-utensils',
    title: 'Recettes intégrées',
    description: 'Planifiez vos repas et accédez à une bibliothèque de recettes adaptées à vos besoins.'
  },
  {
    id: 4,
    icon: 'fa-solid fa-bell',
    title: 'Notifications intelligentes',
    description: 'Recevez des rappels personnalisés pour ne rien oublier de vos tâches quotidiennes.'
  }
])
</script>

<template>
  <section id="features" class="py-12 md:py-24 bg-[#FAFAFA]">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
      <div class="text-center mb-8 md:mb-16">
        <h3 class="text-2xl sm:text-3xl md:text-4xl font-['Outfit'] font-bold text-[#333333] mb-3 md:mb-4">
          Fonctionnalités principales
        </h3>
        <p class="text-base md:text-lg text-lighBlue">
          Tout ce dont vous avez besoin pour gérer votre maison efficacement
        </p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
        <FeatureCard
          v-for="feature in features"
          :key="feature.id"
          :icon="feature.icon"
          :title="feature.title"
          :description="feature.description"
        />
      </div>
    </div>
  </section>
</template>