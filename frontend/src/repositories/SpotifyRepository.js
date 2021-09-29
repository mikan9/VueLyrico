import Client from './Client';

const resource = '/api/spotify';

export default {
    getClientId() {
        return Client.get(`${resource}/clientid`);
    },
    authenticate() {
        return Client.get(`${resource}/auth`);
    },
    getAuthUrl() {
        return Client.get(`${resource}/auth`);
    },
    getCurrentlyPlaying(token) {
        return Client.post(`${resource}/currently_playing`, { access_token: token });
    }
}