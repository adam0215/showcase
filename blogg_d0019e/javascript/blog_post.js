// URL Parametrar
urlParamsStr = window.location.search
urlParamsObj = new URLSearchParams(urlParamsStr)

// Leta efter "np" som parameter i urlen vilket innebär nytt inlägg
const isNewPost = urlParamsObj.get('np') ?? false
const isUpdatedPost = urlParamsObj.get('up') ?? false
const currentPostID = urlParamsObj.get('id') ?? null

// Visa meddelande om inlägget är nytt
if (isNewPost) {
	showPageSpecialMessage('Nytt inlägg skapat!')
}

// Visa meddelande om inlägget är uppdaterat
if (isUpdatedPost) {
	showPageSpecialMessage('Inlägget är uppdaterat!')
}

// Funktion för att ta bort alla andra reaktioner, både genom att fråga API:n och i UI:t. Motverkar flera reaktioner från en användare i klienten
async function removeOtherReactions(currentPostID, reactionBtnContainer) {
	// Titta efter alla med "reacted" klassen, dvs aktiva reaktioner
	for (const reactionBtn of reactionBtnContainer.querySelectorAll('.reacted')) {
		// Ta bort reaktion
		deleteReaction(currentPostID).then((result) => {
			// Uppdatera UI:t
			updateReactionUI('remove', reactionBtn, reactionBtnContainer)
			if (result === true) return true
		})
	}
}

// Behållaren med alla reaktionsknapapr i
const reactionBtnContainer = document.getElementById('post-reactions')

// Loopa över alla knappar och lägg på en lyssnare för klick
for (const reactionBtn of reactionBtnContainer.children) {
	reactionBtn.addEventListener('click', (e) => {
		// Reaktionsknapp
		const button = e.target.closest('button')
		// Reaktionstyp
		const reactionType = button.getAttribute('name')

		// Om en reaktionsknapp klickas och det finns ett inläggs-id i urlen
		if (currentPostID) {
			// Om användaren klickar på en aktiv/gjord reaktion ta bort den.
			if (button.classList.contains('reacted')) {
				deleteReaction(currentPostID)
				updateReactionUI('remove', button, reactionBtnContainer)
			} else {
				// Annars ta bort ev. andra reaktioner (som inte ska existera om allt annat fungerar) och lägg till en ny reaktion
				removeOtherReactions(currentPostID, reactionBtnContainer).then(() => {
					addReaction(currentPostID, reactionType)
					updateReactionUI('add', button, reactionBtnContainer)
				})
			}
		}
	})
}

// Funktion för att uppdatera gränssnittet när en reaktion uppdateras av en användare
function updateReactionUI(action, clickedReactionBtn, reactionBtnContainer) {
	// Uppdatera inte ifall knappen inte har hover-klassen. För tillfället innebär detta att användaren inte är inloggad
	if (!clickedReactionBtn.classList.contains('card-hover')) return

	const reactionType = clickedReactionBtn.getAttribute('name')
	const reactionCountText = clickedReactionBtn.querySelector('span > span')
	// Data-variabel på HTML-elementet som innehåller antalet av just den reaktionen på inlägget
	const reactionCountAttr =
		clickedReactionBtn.getAttribute('data-reactioncount')
	let reactionCount = parseInt(reactionCountAttr)
	// Funktion för att uppdatera antalet reaktioner i data-variabeln på HTML-elementet
	const setNewCount = (newCount) =>
		clickedReactionBtn.setAttribute('data-reactioncount', newCount)

	// Ta bort "reacted"-klassen på andra ev. aktiva reaktionsknappar / Gör andra reaktionsknappar inaktiva
	const clearOtherReacted = (reactionToKeep) => {
		for (const reactionBtn of reactionBtnContainer.children) {
			if (reactionBtn.getAttribute('name') === reactionToKeep)
				reactionBtn.classList.remove('reacted')
		}
	}

	// "Action" bestäms i denna funktionens parametrar
	switch (action) {
		// Om en reaktion ska läggas till
		case 'add': {
			// Ta bort andra aktiva reaktioner
			clearOtherReacted(reactionType)
			// Gör den klickade reaktionsknappen aktiv
			clickedReactionBtn.classList.add('reacted')
			// Uppdatera antalet reaktioner
			reactionCount++
			// Uppdatera elementet med det nya antalet
			setNewCount(reactionCount)
			// Uppdatera antalstexten i knappen
			reactionCountText.innerText = reactionCount
			break
		}
		// Om en reaktion ska tas bort
		case 'remove': {
			// Ta bort andra aktiva reaktioner
			clearOtherReacted(reactionType)
			// Gör den klickade reaktionsknappen inaktiv
			clickedReactionBtn.classList.remove('reacted')
			// Uppdatera antalet reaktioner
			reactionCount--
			// Uppdatera elementet med det nya antalet
			setNewCount(reactionCount)
			// Uppdatera antalstexten i knappen
			reactionCountText.innerText = reactionCount
			break
		}
	}
}

// Funktion för att be API:n lägga till en nya reaktion
function addReaction(postID, reactionType) {
	// Skicka förfrågan till API:n om att lägga till reaktion
	fetch('api/api_post_reaction.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		// Data till API:n
		body: JSON.stringify({
			postID: postID,
			type: reactionType,
			action: 'add',
		}),
	})
		.then((response) => {
			// Vid fel i svaret
			if (!response.ok) {
				throw response.statusText
			}
			return response.json()
		})
		.then((data) => {
			// Framgångsmeddelande
			console.log('Post reacted to successfully:', data)
			return true
		})
		.catch((error) => {
			// Felmeddelande
			console.error('Error reacting to post:', error)
			return false
		})
}

// Funktion för att be API:n ta bort en existerande reaktion
async function deleteReaction(postID) {
	// Skicka förfrågan till API:n om att ta bort reaktion
	return fetch('api/api_post_reaction.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		// Data till API:n
		body: JSON.stringify({
			postID: postID,
			action: 'delete',
		}),
	})
		.then((response) => {
			// Vid fel i svaret
			if (!response.ok) {
				throw response.statusText
			}
			return response.json()
		})
		.then((data) => {
			// Framgångsmeddelande
			console.log('Post reacted to successfully:', data)
			return true
		})
		.catch((error) => {
			// Felmeddelande
			console.error('Error reacting to post:', error)
			return false
		})
}

// Visa specialmeddelande vid nytt inlägg
async function showPageSpecialMessage(message, duration = 3000) {
	return new Promise((resolve, reject) => {
		// Animation
		const animateIn = [
			{
				opacity: '0%',
			},
			{
				opacity: '100%',
			},
		]

		const animateOut = [
			{
				opacity: '100%',
			},
			{
				opacity: '0%',
			},
		]

		const animationTimings = {
			iterations: 1,
			duration: 350,
		}
		// ---

		// Skapa element
		const msgElement = document.createElement('div')
		// Lägg till meddelandestexten
		msgElement.innerText = message
		// CSS-klass för styling
		msgElement.classList.add('page-message', 'styled-message')
		// HTML-attribut för tillgänglighet
		msgElement.setAttribute('aria-live', 'polite')
		msgElement.setAttribute('role', 'alert')
		// Lägg till element i main-elementet
		document.querySelector('main').appendChild(msgElement)
		// Animera in element
		msgElement.animate(animateIn, animationTimings)

		// Sluta visa meddelande efter specifierad tid i funktionsparametrar
		setTimeout(() => {
			const outAnimation = msgElement.animate(animateOut, animationTimings)
			outAnimation.onfinish = () => {
				// Ta bort elementet från DOM när ut-animationen är färdig
				msgElement.remove()
				// Returnera true för att berätta att meddelandet är färdigt och borttaget
				resolve('finished')
			}
		}, duration)
	})
}
