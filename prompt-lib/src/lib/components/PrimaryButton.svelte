<script lang="ts">
	import Icon from '@iconify/svelte'
	import type { HTMLButtonAttributes } from 'svelte/elements'

	export let text: PropButtonText
	export let iconifyIcon: PropButtonIcon = null
	// HTML ATTRIBUTES AS PROP
	export let attributes: HTMLButtonAttributes = {}
</script>

<button
	{...attributes}
	on:click
	class="flex max-w-max justify-between gap-2 rounded bg-emerald-50 px-6 py-2 text-emerald-700 ring-2 ring-emerald-500 transition-all duration-200 hover:bg-emerald-500 hover:text-white hover:ring-offset-2 active:bg-emerald-600 active:text-white"
>
	{text}
	{#if iconifyIcon}
		<Icon icon={iconifyIcon} width={24} />
	{/if}
</button>
