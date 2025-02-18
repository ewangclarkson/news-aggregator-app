import React, { useState } from 'react';
import Loader from '../loader/Loader';
import {useAuth} from "../../context/AuthContext";
import Alert from "../Alert/Alert";
import {SignUpRequest} from "../../models/SignUpRequest";
import icon from "../Assets/innoscripta-logo.svg";
import {LoginRequest} from "../../models/LoginRequest";


const Register: React.FC = () => {
    const guest: SignUpRequest = { name: '', email: '', password: ''};
    const [user, setUser] = useState<SignUpRequest>(guest);
    const [error, setError] = useState<boolean>(false);
    const checkIn = useAuth();

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
       if(validateInput(user)) {
           await checkIn.register(user);
       }
        setUser(guest);
    };

    const validateInput = ({name, email, password }: SignUpRequest): boolean => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isEmailValid = emailRegex.test(email);
        const isPasswordValid = password.length >= 6;
        const isNameValid = name.length > 3;

        return isEmailValid && isPasswordValid && isNameValid;
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const name = e.target.name;
        const value = e.target.value;
        setUser({ ...user, [name]: value });
    };

    return (
        <>
            <Alert
                className="alert alert-danger check-in-alert-block"
                message="All fields are required, password and confirm password fields must be the same"
                show={error}
                reset={checkIn.resetAlert}
            /><br />
            {!checkIn.toggleSignIn && (
                <div className="login__block active" data-ma-theme="green" id="l-register">
                    <div className="login__block__header">
                        <img src={icon}/>
                        Create an account
                    </div>

                    <div className="login__block__body">
                        <form onSubmit={handleSubmit}>
                            <div className="form-group form-group--left">
                                <label>Name</label>
                                <input
                                    type="text"
                                    className="form-control"
                                    name="name"
                                    value={user.name}
                                    onChange={handleChange}
                                />
                                <i className="form-group__bar"></i>
                            </div>
                            <div className="form-group form-group--left">
                                <label>Email Address</label>
                                <input
                                    type="email"
                                    className="form-control"
                                    value={user.email}
                                    name="email"
                                    onChange={handleChange}
                                />
                                <i className="form-group__bar"></i>
                            </div>

                            <div className="form-group form-group--left">
                                <label>Password</label>
                                <input
                                    type="password"
                                    className="form-control"
                                    name="password"
                                    value={user.password}
                                    onChange={handleChange}
                                />
                                <i className="form-group__bar"></i>
                            </div>

                            {checkIn.loading ? <Loader className="center" /> : null}
                            <button
                                type="submit"
                                className="btn login__block__btn"
                                disabled={checkIn.loading}
                            >
                                Register
                            </button>
                        </form>
                        <br /><br />

                        <a className="dropdown-item" onClick={() => checkIn.switchCheckIn(true)}>
                            <i className="zmdi zmdi-long-arrow-right text-green"></i>Already have an account?
                        </a>
                    </div>
                </div>
            )}
        </>
    );
};

export default Register;