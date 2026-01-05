<script setup>
import { ref } from 'vue'
import FaqItem from './FaqItem.vue'

const faqItems = ref([
  {
    id: 1,
    question: 'Est-ce que homeez est gratuit ?',
    answer: 'Oui, homeez propose une version gratuite avec toutes les fonctionnalités essentielles. Des options premium sont disponibles pour les familles ayant des besoins avancés.'
  },
  {
    id: 2,
    question: 'Combien de membres peuvent utiliser homeez ?',
    answer: 'Vous pouvez inviter autant de membres de votre famille que vous le souhaitez. Tout le monde peut collaborer en temps réel sur les tâches et les listes.'
  },
  {
    id: 3,
    question: 'Mes données sont-elles sécurisées ?',
    answer: 'Absolument. Nous utilisons un cryptage de niveau bancaire et ne partageons jamais vos données personnelles avec des tiers. Votre confidentialité est notre priorité.'
  },
  {
    id: 4,
    question: 'Puis-je utiliser homeez sur mobile ?',
    answer: 'Oui, homeez est entièrement responsive et fonctionne parfaitement sur tous les appareils : ordinateurs, tablettes et smartphones.'
  },
  {
    id: 5,
    question: 'Comment puis-je commencer ?',
    answer: 'Il suffit de créer un compte gratuit en quelques secondes. Vous pourrez immédiatement inviter votre famille et commencer à organiser votre maison.'
  }
])
</script>

<template>
  <section id="faq" class="py-12 md:py-24 bg-[#FAFAFA]">
    <div class="max-w-4xl mx-auto px-4 md:px-6">
      <div class="text-center mb-8 md:mb-16">
        <h3 class="text-2xl sm:text-3xl md:text-4xl font-['Outfit'] font-bold text-[#333333] mb-3 md:mb-4">
          Questions fréquentes
        </h3>
        <p class="text-base md:text-lg text-lighBlue">
          Trouvez les réponses aux questions les plus courantes
        </p>
      </div>
      <div class="space-y-4 md:space-y-6">
        <FaqItem
          v-for="item in faqItems"
          :key="item.id"
          :question="item.question"
          :answer="item.answer"
        />
      </div>
    </div>
  </section>
</template>