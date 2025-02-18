import {User} from "./User";

export interface LoginResponse {
    token: string
    refreshToken: string;
    expiresIn: string;
    user: User
}