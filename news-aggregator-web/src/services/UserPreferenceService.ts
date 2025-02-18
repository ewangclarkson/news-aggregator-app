import axiosApi from "../hooks/config/AxiosClient";
import {UserPreferenceRequest} from "../models/UserPreferenceRequest";
import {UserPreferenceResponse} from "../models/UserPreferenceResponse";

class UserPreferenceService{
    constructor() {
    }

    get(): Promise<UserPreferenceResponse> {
        return axiosApi({
            method: 'GET',
            url: '/user/preferences',
        }).then(response => response.data);
    }

    save(userPreferenceRequest: UserPreferenceRequest): Promise<UserPreferenceResponse> {
        return axiosApi({
            method: 'POST',
            url: '/user/preferences',
            data: userPreferenceRequest,
        }).then(response => response.data);
    }
}

export default new UserPreferenceService();