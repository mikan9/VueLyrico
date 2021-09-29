import axios from "axios";

const axiosIns = axios.create({
    baseURL: process.env.BASE_URL,
});

export default axiosIns;