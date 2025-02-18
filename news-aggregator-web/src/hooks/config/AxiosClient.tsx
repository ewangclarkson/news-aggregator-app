import axios, { AxiosInstance } from 'axios';
import StorageService from "../../services/StorageService";

const axiosApi: AxiosInstance = axios.create({
    baseURL: 'http://localhost:8000/api/', // Set your base URL here
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(StorageService.get('token') ? { Authorization: `Bearer ${StorageService.get('token')}` } : {}),
    },
});

export default axiosApi;