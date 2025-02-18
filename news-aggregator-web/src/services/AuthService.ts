import {LoginRequest} from '../models/LoginRequest';
import {LoginResponse} from '../models/LoginResponse';
import axiosApi from "../hooks/config/AxiosClient";
import {SignUpRequest} from "../models/SignUpRequest";
import StorageService from "./StorageService";

class AuthService {
    constructor() {
    }

    register(registerUserRequest: SignUpRequest): Promise<string> {
        return axiosApi({
            method: 'POST',
            url: '/register',
            data: registerUserRequest,
        }).then(response => response.data);
    }

    login(loginRequest: LoginRequest): Promise<LoginResponse> {
        return axiosApi({
            method: 'POST',
            url: '/login',
            data: loginRequest,
        }).then(response => response.data);
    }

    logout(): Promise<string> {
        return axiosApi({
            method: 'POST',
            url: '/logout',
        }).then(response => response.data);
    }


    async isAuth(): Promise<boolean> {
        if (StorageService.isLoggedIn()) {
            return true;
        }

        // Attempt to refresh the token
        const refreshToken = StorageService.getRefreshToken();
        if (refreshToken) {
            try {
                const response = await axiosApi({
                    method: 'POST',
                    url: '/refresh',
                    data: { refreshToken },
                });

                StorageService.login(response.data);

                return true;
            } catch (error) {
                StorageService.logout();
                return false;
            }
        }

        return false;
    }
}

export default new AuthService();