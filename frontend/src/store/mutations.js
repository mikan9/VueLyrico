export default {
    UPDATE_SPOTIFY(state, payload) {
        state.spotify = {
            ...state.spotify,
            ...payload
        }
    }
}