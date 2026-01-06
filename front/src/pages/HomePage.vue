<script setup>
import NavBarLanding from '@/components/NavBarLanding.vue'
import HeroSection from '@/components/landing/HeroSection.vue'
import TaglineSection from '@/components/landing/TaglineSection.vue'
import FeaturesSection from '@/components/landing/FeaturesSection.vue'
import BenefitsSection from '@/components/landing/BenefitsSection.vue'
import CtaSection from 
'@/components/landing/CtaSection.vue'
import FaqSection from '@/components/landing/FaqSection.vue'
import ContactSection from '@/components/landing/ContactSection.vue'
import AppFooter from '@/components/landing/AppFooter.vue'
</script>

<template>
  <div class="bg-[#FAFAFA] font-['Inter'] text-[#333333]">
    <NavBarLanding />
    
    <main class="pt-20">
      <HeroSection />
      
      <TaglineSection />
      
      <FeaturesSection />
      
      <BenefitsSection />
      
      <!-- CTA Middle -->
      <CtaSection
        title="Prêt à simplifier votre vie ?"
        description="Rejoignez des milliers de familles qui ont déjà transformé leur quotidien avec homeez."
        button-text="Rejoignez-nous gratuitement"
        variant="solid"
      />
      
      <FaqSection />
      
      <ContactSection />
      
      <!-- CTA Final -->
      <CtaSection
        title="Commencez votre essai gratuit dès aujourd'hui"
        description="Aucune carte de crédit requise. Annulation possible à tout moment."
        button-text="Créer mon compte gratuit"
        variant="gradient"
      />
    </main>
    
    <AppFooter />
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');

::-webkit-scrollbar {
  display: none;
}
</style>