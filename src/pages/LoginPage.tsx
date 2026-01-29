import { useState } from "react";
import { useNavigate } from "react-router-dom";
import AuthLayout from "../components/auth/AuthLayout";
import AuthInput from "../components/auth/AuthInput";
import PrimaryButton from "../components/ui/PrimaryButton";
import { Link } from "react-router-dom";

export default function LoginPage() {
  const navigate = useNavigate();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const emailError =
    email && !email.includes("@") ? "Enter a valid email address" : "";
  const passwordError =
    password && password.length < 6 ? "Password too short" : "";

  const isValid = email && password && !emailError && !passwordError;

  function handleSubmit() {
    if (!isValid) return;

    // TEMP: simulate successful login
    navigate("/");
  }

  return (
    <AuthLayout
      title="Welcome Back to Excellence."
      description="Dive back into your courses and pick up right where you left off. Your next big achievement is waiting."
    >
      <h2 style={{ marginBottom: 8 }}>Sign In</h2>
      <p style={{ color: "#64748b", marginBottom: 32 }}>
        Please enter your details below.
      </p>

      <AuthInput
        label="Email Address"
        placeholder="name@company.com"
        value={email}
        onChange={setEmail}
        error={emailError}
      />

      <AuthInput
        label="Password"
        type="password"
        value={password}
        onChange={setPassword}
        error={passwordError}
        rightLink={
          <a href="#" style={{ fontSize: 12, color: "#2f66e6" }}>
            FORGOT?
          </a>
        }
      />

      <div onClick={handleSubmit}>
        <PrimaryButton disabled={!isValid}>Sign In</PrimaryButton>
      </div>

      <p style={{ marginTop: 32, textAlign: "center", color: "#64748b" }}>
        Don’t have an account?{" "}
        <Link to="/register" style={{ color: "#2f66e6", fontWeight: 600 }}>
          Sign up for free
        </Link>
      </p>
    </AuthLayout>
  );
}
