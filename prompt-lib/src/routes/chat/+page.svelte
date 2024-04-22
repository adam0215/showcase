<script lang="ts">
	import { enhance } from '$app/forms'
	import ChatMessage from '$lib/components/ChatMessage.svelte'
	import Icon from '@iconify/svelte'
	import { flip } from 'svelte/animate'
	import { quintInOut } from 'svelte/easing'
	import { fade } from 'svelte/transition'

	export let data
	export let form

	const presetPrompt: PresetPrompt = data.shortPrompt ? data.shortPrompt : null
	let loadingApiResponse = false
	let chatLog = new Set<AiChatMessage>([])

	function handleUserChatSubmit(event: SubmitEvent) {
		const formEl = event.target as HTMLFormElement

		// Set loading response to true
		loadingApiResponse = true

		const formData = new FormData(formEl)
		const userPrompt = formData.get('user-prompt') ?? ''

		console.log(`User prompt: ${userPrompt}`)

		if (!userPrompt) return { success: false }

		const formattedUserPrompt = userPrompt.toString().trim()
		const chatObj: AiChatMessage = { role: 'user', content: formattedUserPrompt }

		chatLog.add(chatObj)

		// Refresh state after change
		chatLog = chatLog

		return { success: true, message: chatObj }
	}

	function handleAiResponse(response: RemoteChatResponse) {
		if (!response.chatResponse) return

		loadingApiResponse = false

		console.log(response)
		chatLog.add(response.chatResponse)

		// Refresh state after change
		chatLog = chatLog
	}

	$: if (form?.success) {
		handleAiResponse(form as RemoteChatResponse)
	} else {
		loadingApiResponse = false
	}

	$: if (chatLog) {
		handleChatScroll()
	}

	let chatEl: HTMLUListElement
	let userControlsChatScroll = false

	function handleChatScroll() {
		if (chatEl) {
			// Dont run if user controls scroll
			if (userControlsChatScroll) return

			chatEl.scroll({ top: chatEl.scrollHeight, behavior: 'smooth' })

			// Reset scroll to automatic
			userControlsChatScroll = false
		}
	}

	function handleUserScroll() {
		if (chatEl) {
			if (chatEl.scrollTop !== chatEl.scrollHeight) userControlsChatScroll = true
			const userHasScrolled = chatEl.clientHeight !== chatEl.scrollHeight - chatEl.scrollTop

			if (!userHasScrolled) userControlsChatScroll = false
			else userControlsChatScroll = true

			console.log(userControlsChatScroll)
		}
	}
</script>

<svelte:head>
	<title>Chat - PROMPTLIB.</title>
</svelte:head>

<div class="flex h-full flex-col items-center">
	<div class="mb-12 flex w-full items-center justify-between">
		<h1 class="mb-0">Chat</h1>
		<div class="mr-1 flex items-center gap-1 overflow-hidden rounded-md px-2 ring-2 ring-gray-500">
			<Icon icon="solar:cpu-linear" width={24} />
			<select
				name="chat-model"
				id="chat-model"
				class="cursor-pointer appearance-none p-2 text-right outline-none"
			>
				<option selected value="gpt-3.5-turbo">GPT-3.5</option>
			</select>
		</div>
	</div>
	<!-- CHAT CONTAINER -->
	<div
		class="flex h-full w-full flex-col items-center justify-end overflow-scroll rounded-lg bg-gray-100 p-4 pb-32"
	>
		<!-- EMPTY STATE -->

		{#if chatLog.size < 1}
			<div
				class="flex max-w-xs flex-col items-center justify-center gap-4 text-center text-gray-300"
			>
				<Icon icon="line-md:coffee-loop" width={48} />
				<p>
					No messages in sight! Send your first message via the chat box.
					{#if /* If there is a preset prompt */ presetPrompt}
						We filled in your prompt for you however!
					{/if}
				</p>
			</div>
		{/if}

		<!-- MESSAGES WRAPPER -->
		<ul
			class="flex w-full flex-col items-center overflow-y-scroll"
			bind:this={chatEl}
			on:scroll={handleUserScroll}
		>
			<!-- MESSAGE -->
			{#each chatLog as message, i (i)}
				<li
					in:fade={{ delay: 200, duration: 100, easing: quintInOut }}
					animate:flip={{ duration: 500, easing: quintInOut }}
					class="flex w-full justify-center"
				>
					<ChatMessage content={message.content} authorType={message.role} />
				</li>
			{/each}
		</ul>
	</div>
	<!-- CHAT -->
	<div class="fixed bottom-0 flex w-full max-w-lg flex-col items-center gap-4 pb-12 lg:max-w-2xl">
		<form
			on:submit|preventDefault={handleUserChatSubmit}
			use:enhance
			method="post"
			action="?/callOpenAiThree"
			class="flex w-full items-center gap-2 overflow-hidden rounded-md bg-white px-4 shadow-lg shadow-gray-500/30 ring-2 ring-gray-300"
		>
			<input
				autocomplete="off"
				value={presetPrompt ? presetPrompt : ''}
				type="text"
				class="h-full w-full py-4 outline-none placeholder:text-gray-500"
				name="user-prompt"
				placeholder="Send a message"
			/>
			<button class="aspect-square h-full text-emerald-500" type="submit">
				{#if loadingApiResponse}
					<span class="contents">
						<Icon icon="svg-spinners:3-dots-scale" width={32} />
					</span>
				{:else}
					<Icon icon="solar:square-double-alt-arrow-right-bold" width={32} />
				{/if}
			</button>
		</form>
		<span class="text-sm text-gray-500">Prototype</span>
	</div>
</div>
