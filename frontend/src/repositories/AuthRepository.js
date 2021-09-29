import Client from './Client';

const resource = '/api';

export default {
    getCSRFToken() {
        return Client.get(`${resource}/token`);
    }
}