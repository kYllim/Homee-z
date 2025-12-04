<script setup>
import { ref, reactive } from 'vue'

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


const smoothScroll = (target) => {
  const element = document.querySelector(target)
  if (element) {
    const headerOffset = 80
    const elementPosition = element.getBoundingClientRect().top
    const offsetPosition = elementPosition + window.pageYOffset - headerOffset
    
    window.scrollTo({
      top: offsetPosition,
      behavior: 'smooth'
    })
  }
}

const submitContactForm = () => {
  console.log('Formulaire soumis:', contactForm)
  alert('Message envoyé avec succès !')
  contactForm.name = ''
  contactForm.email = ''
  contactForm.message = ''
}
</script>

<template>
  <div class="bg-[#FAFAFA] font-['Inter'] text-[#333333]">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 bg-[#FAFAFA] shadow-sm z-50">
      <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center">
          <h1 class="text-3xl font-['Outfit'] font-bold text-[#333333]">homeez</h1>
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a @click.prevent="smoothScroll('#features')" href="#features" class="text-[#333333] hover:text-[#9CBFA2] transition font-medium cursor-pointer">Fonctionnalités</a>
          <a @click.prevent="smoothScroll('#benefits')" href="#benefits" class="text-[#333333] hover:text-[#9CBFA2] transition font-medium cursor-pointer">Avantages</a>
          <a @click.prevent="smoothScroll('#faq')" href="#faq" class="text-[#333333] hover:text-[#9CBFA2] transition font-medium cursor-pointer">FAQ</a>
          <a @click.prevent="smoothScroll('#contact')" href="#contact" class="text-[#333333] hover:text-[#9CBFA2] transition font-medium cursor-pointer">Contact</a>
        </div>
        <div class="flex items-center space-x-4">
          <button class="px-6 py-2.5 text-[#333333] hover:text-[#9CBFA2] transition font-medium">Connexion</button>
          <button class="px-6 py-2.5 bg-[#9CBFA2] text-white rounded-lg hover:bg-opacity-90 transition font-medium">S'inscrire</button>
        </div>
      </nav>
    </header>

    <main class="pt-20">
      <!-- Hero Section -->
      <section class="h-[680px] bg-gradient-to-br from-[#F5EEDC] to-[#FAFAFA] flex items-center">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
          <div class="space-y-6">
            <h2 class="text-5xl font-['Outfit'] font-bold text-[#333333] leading-tight">Simplifiez la gestion de votre maison</h2>
            <p class="text-xl text-[#90A4AE] font-['Inter']">Organisez vos tâches ménagères, partagez vos listes de courses et gérez votre emploi du temps familial en un seul endroit.</p>
            <div class="flex items-center space-x-4 pt-4">
              <button class="px-8 py-3.5 bg-[#9CBFA2] text-white rounded-xl hover:bg-opacity-90 transition font-medium text-lg">Commencer gratuitement</button>
              <button class="px-8 py-3.5 border-2 border-[#9CBFA2] text-[#9CBFA2] rounded-xl hover:bg-[#9CBFA2] hover:text-white transition font-medium text-lg">En savoir plus</button>
            </div>
          </div>
          <div class="flex justify-center">
            <div class="w-full h-96 bg-white rounded-3xl shadow-lg p-8 flex items-center justify-center">
              <i class="fa-solid fa-house text-[#9CBFA2] text-9xl opacity-20"></i>
            </div>
          </div>
        </div>
      </section>

      <!-- Tagline Section -->
      <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6 text-center">
          <h3 class="text-4xl font-['Outfit'] font-bold text-[#333333] mb-6">Une maison organisée, une vie simplifiée</h3>
          <p class="text-xl text-[#90A4AE] leading-relaxed">Avec homeez, transformez la gestion quotidienne de votre foyer en une expérience fluide et collaborative. Plus de temps pour ce qui compte vraiment.</p>
        </div>
      </section>

      <!-- Features Section -->
      <section id="features" class="py-24 bg-[#FAFAFA]">
        <div class="max-w-7xl mx-auto px-6">
          <div class="text-center mb-16">
            <h3 class="text-4xl font-['Outfit'] font-bold text-[#333333] mb-4">Fonctionnalités principales</h3>
            <p class="text-lg text-[#90A4AE]">Tout ce dont vous avez besoin pour gérer votre maison efficacement</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="feature in features" :key="feature.id" class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition">
              <div class="w-16 h-16 bg-[#9CBFA2] bg-opacity-10 rounded-xl flex items-center justify-center mb-6">
                <i :class="feature.icon" class="text-[#9CBFA2] text-3xl"></i>
              </div>
              <h4 class="text-xl font-['Outfit'] font-semibold text-[#333333] mb-3">{{ feature.title }}</h4>
              <p class="text-[#90A4AE]">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Benefits Section -->
      <section id="benefits" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
          <div class="text-center mb-16">
            <h3 class="text-4xl font-['Outfit'] font-bold text-[#333333] mb-4">Pourquoi choisir homeez ?</h3>
            <p class="text-lg text-[#90A4AE]">Découvrez les avantages d'une gestion domestique moderne</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
              <div v-for="benefit in benefits" :key="benefit.id" class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-[#F5EEDC] rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                  <i :class="benefit.icon" class="text-[#9CBFA2] text-xl"></i>
                </div>
                <div>
                  <h4 class="text-xl font-['Outfit'] font-semibold text-[#333333] mb-2">{{ benefit.title }}</h4>
                  <p class="text-[#90A4AE]">{{ benefit.description }}</p>
                </div>
              </div>
            </div>
            <div class="flex justify-center">
              <div class="w-full h-[500px] bg-gradient-to-br from-[#9CBFA2] to-[#90A4AE] rounded-3xl shadow-xl flex items-center justify-center">
                <i class="fa-solid fa-home text-white text-9xl opacity-30"></i>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Middle -->
      <section class="py-20 bg-[#9CBFA2]">
        <div class="max-w-5xl mx-auto px-6 text-center">
          <h3 class="text-4xl font-['Outfit'] font-bold text-white mb-6">Prêt à simplifier votre vie ?</h3>
          <p class="text-xl text-white opacity-90 mb-8">Rejoignez des milliers de familles qui ont déjà transformé leur quotidien avec homeez.</p>
          <button class="px-10 py-4 bg-white text-[#9CBFA2] rounded-xl hover:bg-opacity-90 transition font-semibold text-lg shadow-lg">Rejoignez-nous gratuitement</button>
        </div>
      </section>

      <!-- FAQ Section -->
      <section id="faq" class="py-24 bg-[#FAFAFA]">
        <div class="max-w-4xl mx-auto px-6">
          <div class="text-center mb-16">
            <h3 class="text-4xl font-['Outfit'] font-bold text-[#333333] mb-4">Questions fréquentes</h3>
            <p class="text-lg text-[#90A4AE]">Trouvez les réponses aux questions les plus courantes</p>
          </div>
          <div class="space-y-6">
            <div v-for="item in faqItems" :key="item.id" class="bg-white rounded-2xl p-8 shadow-md">
              <h4 class="text-xl font-['Outfit'] font-semibold text-[#333333] mb-3">{{ item.question }}</h4>
              <p class="text-[#90A4AE]">{{ item.answer }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6">
          <div class="text-center mb-16">
            <h3 class="text-4xl font-['Outfit'] font-bold text-[#333333] mb-4">Contactez-nous</h3>
            <p class="text-lg text-[#90A4AE]">Une question ? Une suggestion ? Nous sommes là pour vous écouter</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="space-y-8">
              <div v-for="info in contactInfo" :key="info.id" class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-[#9CBFA2] bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i :class="info.icon" class="text-[#9CBFA2] text-xl"></i>
                </div>
                <div>
                  <h4 class="text-lg font-['Outfit'] font-semibold text-[#333333] mb-1">{{ info.title }}</h4>
                  <p class="text-[#90A4AE]">{{ info.value }}</p>
                </div>
              </div>
            </div>
            <div class="bg-[#FAFAFA] rounded-2xl p-8">
              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-medium text-[#333333] mb-2">Nom complet</label>
                  <input 
                    v-model="contactForm.name"
                    type="text" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#9CBFA2] focus:ring-2 focus:ring-[#9CBFA2] focus:ring-opacity-20 outline-none transition" 
                    placeholder="Jean Dupont"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-[#333333] mb-2">Email</label>
                  <input 
                    v-model="contactForm.email"
                    type="email" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#9CBFA2] focus:ring-2 focus:ring-[#9CBFA2] focus:ring-opacity-20 outline-none transition" 
                    placeholder="jean.dupont@email.fr"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-[#333333] mb-2">Message</label>
                  <textarea 
                    v-model="contactForm.message"
                    rows="4" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#9CBFA2] focus:ring-2 focus:ring-[#9CBFA2] focus:ring-opacity-20 outline-none transition resize-none" 
                    placeholder="Votre message..."
                  ></textarea>
                </div>
                <button 
                  @click="submitContactForm" 
                  type="button" 
                  class="w-full px-6 py-3.5 bg-[#9CBFA2] text-white rounded-lg hover:bg-opacity-90 transition font-medium"
                >
                  Envoyer le message
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Final -->
      <section class="py-20 bg-gradient-to-r from-[#9CBFA2] to-[#90A4AE]">
        <div class="max-w-5xl mx-auto px-6 text-center">
          <h3 class="text-4xl font-['Outfit'] font-bold text-white mb-6">Commencez votre essai gratuit dès aujourd'hui</h3>
          <p class="text-xl text-white opacity-90 mb-8">Aucune carte de crédit requise. Annulation possible à tout moment.</p>
          <button class="px-10 py-4 bg-white text-[#9CBFA2] rounded-xl hover:bg-opacity-90 transition font-semibold text-lg shadow-lg">Créer mon compte gratuit</button>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-[#333333] text-white py-16">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
          <div>
            <h2 class="text-3xl font-['Outfit'] font-bold mb-4">homeez</h2>
            <p class="text-gray-400 mb-6">Simplifiez la gestion de votre maison avec notre plateforme tout-en-un.</p>
            <div class="flex space-x-4">
              <a v-for="social in socialLinks" :key="social.name" :href="social.url" class="w-10 h-10 bg-white bg-opacity-10 rounded-lg flex items-center justify-center hover:bg-[#9CBFA2] transition">
                <i :class="social.icon" class="text-white"></i>
              </a>
            </div>
          </div>
          <div>
            <h4 class="text-lg font-['Outfit'] font-semibold mb-4">Produit</h4>
            <ul class="space-y-3">
              <li v-for="link in footerLinks.product" :key="link.text">
                <a :href="link.url" class="text-gray-400 hover:text-[#9CBFA2] transition">{{ link.text }}</a>
              </li>
            </ul>
          </div>
          <div>
            <h4 class="text-lg font-['Outfit'] font-semibold mb-4">Entreprise</h4>
            <ul class="space-y-3">
              <li v-for="link in footerLinks.company" :key="link.text">
                <a :href="link.url" class="text-gray-400 hover:text-[#9CBFA2] transition">{{ link.text }}</a>
              </li>
            </ul>
          </div>
          <div>
            <h4 class="text-lg font-['Outfit'] font-semibold mb-4">Légal</h4>
            <ul class="space-y-3">
              <li v-for="link in footerLinks.legal" :key="link.text">
                <a :href="link.url" class="text-gray-400 hover:text-[#9CBFA2] transition">{{ link.text }}</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-700 pt-8 text-center">
          <p class="text-gray-400">&copy; 2024 homeez. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  </div>
</template> 

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');

::-webkit-scrollbar {
  display: none;
}
</style>
