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
    getCurrentlyPlaying() {
        return Client.get(`${resource}/currently_playing`);
    }
}