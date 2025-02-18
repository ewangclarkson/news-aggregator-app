import {User} from "../models/User";
import {LoginResponse} from "../models/LoginResponse";

const USER_KEY = 'user';
const TOKEN_EXPIRY_DATE = 'expiresIn';
const TOKEN = 'token';
const REFRESH_TOKEN = 'refreshToken';

class StorageService {
    constructor() {
    }

    public set(key: string, value: string): void {
        localStorage.setItem(key, value);
    }

    public get(key: string): string | null {
        return localStorage.getItem(key);
    }

    public remove(key: string): void {
        localStorage.removeItem(key);
    }

    public clear(): void {
        localStorage.clear();
    }

    public hasValue(key: string): boolean {
        return localStorage.getItem(key) !== null;
    }

    public getCurrentUser(): User | null {
        const user = this.get(USER_KEY);
        return user ? JSON.parse(user) : null;
    }

    public isLoggedIn(): boolean {
        const user = this.get(USER_KEY);
        const expiresIn = this.get(TOKEN_EXPIRY_DATE);

        return (user !== null && new Date() <= new Date(expiresIn!));
    }

    public getRefreshToken() {
        return localStorage.getItem(REFRESH_TOKEN);
    }

    public logout(): void {
        this.remove(USER_KEY);
        this.remove(TOKEN_EXPIRY_DATE);
        this.remove(REFRESH_TOKEN);
        this.remove(TOKEN);
    }

    public login(authData: LoginResponse) {
        Object.entries(authData).forEach(([key, value]) => {
            const item = (key === "user" ? JSON.stringify(value): value);
            this.set(key,item);
        });
    }
}

export default new StorageService();