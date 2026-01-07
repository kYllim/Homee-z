<template>
  <section class="min-h-[500px] md:min-h-[680px] bg-linear-to-br from-[#F5EEDC] to-[#FAFAFA] flex items-center py-20">
    <div class="max-w-7xl mx-auto px-4 md:px-6 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center w-full">
      <div class="space-y-4 md:space-y-6 text-center md:text-left">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-['Outfit'] font-bold text-[#333333] leading-tight">
          Simplifiez la gestion de votre maison
        </h2>
        <p class="text-base sm:text-lg md:text-xl text-lighBlue font-['Inter']">
          Organisez vos tâches ménagères, partagez vos listes de courses et gérez votre emploi du temps familial en un seul endroit.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-3 sm:gap-4 pt-4">
          <button class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-3.5 bg-green_pastel text-white rounded-xl hover:bg-opacity-90 transition font-medium text-base md:text-lg whitespace-nowrap">
            Commencer gratuitement
          </button>
          <button class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-3.5 border-2 border-green_pastel text-green_pastel rounded-xl hover:bg-green_pastel hover:text-white transition font-medium text-base md:text-lg whitespace-nowrap">
            En savoir plus
          </button>
        </div>
      </div>
      <div class="flex justify-center mt-6 md:mt-0">
        <div class="w-full h-64 md:h-96 bg-white rounded-2xl md:rounded-3xl shadow-lg p-6 md:p-8 flex items-center justify-center">
          <i class="fa-solid fa-house text-green_pastel text-6xl md:text-9xl opacity-20"></i>
        </div>
      </div>
    </div>
  </section>
</template>