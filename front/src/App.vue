<script setup>
// Root app — navigation will be handled by the router
</script>

<template>
	<div>
		<nav>
			<a href="/">Home</a> |
			<a href="/dashboard">Dashboard</a>
		</nav>

		<main>
			<router-view />
		</main>
	</div>
</template>

<style>
/* No custom styling here per request */
</style>
