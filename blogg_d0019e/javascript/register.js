const profilePicture = document.getElementById('profile-pic')
const imgSelectorContainer = document.getElementById('img-selector-container')

// Lägg till lyssnare för om användaren lägger till eller byter fil
profilePicture.addEventListener('change', (e) => {
	// Den uppladdade bilden
	const selectedImg = profilePicture.files[0]
	// Skapa bloburl från den uppladdade bilden
	const blobURL = URL.createObjectURL(selectedImg)

	// Sätt bakgrundsbilden till den valda bilden
	imgSelectorContainer.style.backgroundImage = `url(${blobURL}`
	// Se till så den fyller hela förhandsvisningen/bildväljaren
	imgSelectorContainer.style.backgroundSize = 'cover'
	imgSelectorContainer.classList.add('styled-img-selector-selected')
})
