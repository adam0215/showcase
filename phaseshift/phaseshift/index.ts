import { app } from './app'

let counter = 0

const state = {
	counter: counter,
	prevAction: 'none',
}

app({
	port: 3000,
	procedures: {
		increment: () => {
			counter++
			state.counter = counter
			state.prevAction = 'incremented'
		},
		decrement: () => {
			counter--
			state.counter = counter
			state.prevAction = 'decremented'
		},
	},
	state: state,
})
