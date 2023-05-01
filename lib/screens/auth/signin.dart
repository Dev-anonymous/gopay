import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_fonts/google_fonts.dart';

import '../../constants.dart';
import '../../controllers/auth_controller.dart';
import '../splash.dart';

class SigninPage extends StatefulWidget {
  @override
  State<SigninPage> createState() => _SigninPageState();
}

class _SigninPageState extends State<SigninPage> {
  AuthController authController = Get.put(AuthController());

  final _formKey = GlobalKey<FormState>();

  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: primaryColor.withOpacity(0.25),
      body: Container(
        decoration: const BoxDecoration(
            image: DecorationImage(
                image: AssetImage(
                  'assets/images/bruce.jpg',
                ),
                fit: BoxFit.cover,
                opacity: 0.6)),
        child: Center(
          child: Padding(
            padding: const EdgeInsets.all(32.0),
            child: Container(
              width: 350,
              //  height: 420,
              decoration: BoxDecoration(
                  color: primaryColor,
                  borderRadius: BorderRadius.circular(10),
                  boxShadow: const [
                    BoxShadow(
                      offset: Offset(0, 15),
                      blurRadius: 27,
                      color: Colors.black12,
                    ),
                  ]),
              child: Form(
                key: _formKey,
                child: Padding(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 16.0, vertical: 24),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        "GoPay",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 32),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Text(
                        "Connexion",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.w500,
                              fontSize: 16),
                        ),
                      ),
                      Center(
                        child: Text(
                          "Veuillez vous connecter pour continuer",
                          style: GoogleFonts.poppins(
                            textStyle: TextStyle(
                                color: Colors.white.withOpacity(0.7),
                                // fontWeight: FontWeight.w500,
                                fontSize: 13),
                          ),
                        ),
                      ),
                      const SizedBox(height: 24),
                      TextFormField(
                        controller: _emailController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir votre identifiant';
                          } else if (!value.isEmail && !value.isPhoneNumber) {
                            return 'Cet identifiant n\'est pas valide';
                          }

                          return null;
                        }),
                        keyboardType: TextInputType.text,
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.black,
                              fontWeight: FontWeight.w400,
                              fontSize: 12),
                        ),
                        decoration: InputDecoration(
                          hintText: "Email ou numero de telephone",
                          hintStyle: GoogleFonts.poppins(
                            textStyle: const TextStyle(
                                color: Colors.black,
                                fontWeight: FontWeight.w400,
                                fontSize: 12),
                          ),
                          errorStyle: GoogleFonts.poppins(
                            textStyle: const TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.w400,
                                fontSize: 12),
                          ),
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                        ),
                      ),
                      const SizedBox(height: defaultPadding),
                      TextFormField(
                        controller: _passwordController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir un mot de passe';
                          } else if (value.length < 6) {
                            return 'Mot de passe trop court. Min 6';
                          }

                          return null;
                        }),
                        obscureText: true,
                        keyboardType: TextInputType.text,
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.black,
                              fontWeight: FontWeight.w400,
                              fontSize: 12),
                        ),
                        decoration: InputDecoration(
                          hintText: "Mot de passe",
                          hintStyle: GoogleFonts.poppins(
                            textStyle: const TextStyle(
                                color: Colors.black,
                                fontWeight: FontWeight.w400,
                                fontSize: 12),
                          ),
                          errorStyle: GoogleFonts.poppins(
                            textStyle: const TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.w400,
                                fontSize: 12),
                          ),
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                          suffixIcon: InkWell(
                            onTap: () {},
                            child: const Icon(
                              Icons.password,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 32),
                      ElevatedButton.icon(
                        style: TextButton.styleFrom(
                          disabledBackgroundColor: Colors.grey,
                          backgroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(
                            horizontal: 32,
                            vertical: 16,
                          ),
                        ),
                        onPressed: () async {
                          print(_emailController.text.trim());
                          print(_passwordController.text.trim());
                          if (_formKey.currentState!.validate()) {
                            authController.login(_emailController.text.trim(),
                                _passwordController.text.trim());
                          }
                        },
                        icon: const Icon(
                          Icons.login,
                          color: primaryColor,
                        ),
                        label: Text(
                          "Se connecter",
                          style: GoogleFonts.poppins(
                            textStyle: const TextStyle(
                                color: Colors.black,
                                fontWeight: FontWeight.w500,
                                fontSize: 12),
                          ),
                        ),
                      ),
                      const SizedBox(
                        height: 24,
                      ),
                      Obx(() => authController.isLoading
                          ? const LinearProgressIndicator(
                              color: Colors.white,
                            )
                          : const SizedBox()),
                      const SizedBox(
                        height: 12,
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
