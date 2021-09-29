import Client from '@/libs/axios';

Client.interceptors.request.use((config) => {



    return config;
}, (error) => {
    // Do something with request error
    return Promise.reject(error);
});


Client.interceptors.response.use((response) => {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, (error) => {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});

export default Client;