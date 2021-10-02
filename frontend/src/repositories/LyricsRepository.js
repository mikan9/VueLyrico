import Client from './Client';

const resource = '/api/lyrics';

export default {
    show(source_type, artist, title) {
        return Client.get(`${resource}/${source_type}/${artist}/${title}`);
    },
}