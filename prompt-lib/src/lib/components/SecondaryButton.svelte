<script lang="ts">
	import type { HTMLButtonAttributes } from 'svelte/elements'

	export let text: PropButtonText

	// HTML ATTRIBUTES AS PROP
	export let attributes: HTMLButtonAttributes = {}
</script>

<button
	{...attributes}
	on:click
	class="rounded bg-inherit px-6 py-2 text-emerald-700 ring-2 ring-emerald-300 transition-all duration-200 hover:bg-emerald-300 hover:text-white hover:ring-offset-2 active:bg-emerald-400 active:text-white"
	>{text}</button
>
