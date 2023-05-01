import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../constants.dart';
import '../../controllers/auth_controller.dart';
import '../../routes/app_pages.dart';
import 'signin.dart';

class SignupPage extends StatefulWidget {
  @override
  State<SignupPage> createState() => _SignupPageState();
}

class _SignupPageState extends State<SignupPage> {
  AuthController authController = Get.put(AuthController());

  final _formKey = GlobalKey<FormState>();

  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    _nameController.dispose();
    _lastNameController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Center(
          child: Padding(
            padding: const EdgeInsets.all(32.0),
            child: Container(
              width: 350,
              decoration: BoxDecoration(
                  color: secondaryColor,
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
                      horizontal: 16.0, vertical: 32),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        "Nouveau compte",
                        style: Theme.of(context).textTheme.headline6,
                      ),
                      Text("Vous avez besoin d'un compte pour continuer",
                          style: Theme.of(context)
                              .textTheme
                              .subtitle1
                              ?.copyWith(
                                  color: Colors.white.withOpacity(0.5),
                                  fontSize: 12)),
                      const SizedBox(height: 32),
                      TextFormField(
                        controller: _nameController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir le nom';
                          } else if (value.length < 3) {
                            return 'Le nom est trop court. (Min 3)';
                          }

                          return null;
                        }),
                        keyboardType: TextInputType.text,
                        maxLines: 1,
                        decoration: InputDecoration(
                          hintText: "Nom",
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                        ),
                      ),
                      SizedBox(height: defaultPadding),
                      TextFormField(
                        controller: _lastNameController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir le Prénom';
                          } else if (value.length < 3) {
                            return 'Le Prénom est trop court. (Min 3)';
                          }

                          return null;
                        }),
                        keyboardType: TextInputType.text,
                        maxLines: 1,
                        decoration: InputDecoration(
                          hintText: "Prénom",
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
                        controller: _phoneController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir le numero de telephone';
                          } else if (!value.isPhoneNumber) {
                            return 'Le numero est invalide';
                          } else if (value.length < 10) {
                            return 'Le numero est trop court';
                          }

                          return null;
                        }),
                        keyboardType: TextInputType.phone,
                        decoration: InputDecoration(
                          hintText: "Téléphone",
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                          suffixIcon: InkWell(
                            onTap: () {},
                            child: Container(
                              padding:
                                  const EdgeInsets.all(defaultPadding * 0.75),
                              margin: const EdgeInsets.symmetric(
                                  horizontal: defaultPadding / 2),
                              decoration: const BoxDecoration(
                                color: primaryColor,
                                borderRadius:
                                    BorderRadius.all(Radius.circular(10)),
                              ),
                              child: const Icon(Icons.phone),
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: defaultPadding),
                      TextFormField(
                        controller: _emailController,
                        validator: ((value) {
                          if (value == null || value.isEmpty) {
                            return 'Veuillez saisir votre email';
                          } else if (!value.isEmail) {
                            return 'Cet email est invalide';
                          }

                          return null;
                        }),
                        keyboardType: TextInputType.emailAddress,
                        decoration: InputDecoration(
                          hintText: "Adresse email",
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                          suffixIcon: InkWell(
                            onTap: () {},
                            child: Container(
                              padding:
                                  const EdgeInsets.all(defaultPadding * 0.75),
                              margin: const EdgeInsets.symmetric(
                                  horizontal: defaultPadding / 2),
                              decoration: const BoxDecoration(
                                color: primaryColor,
                                borderRadius:
                                    BorderRadius.all(Radius.circular(10)),
                              ),
                              child: const Icon(Icons.email),
                            ),
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
                        decoration: InputDecoration(
                          hintText: "Mot de passe",
                          fillColor: bgColor,
                          filled: true,
                          border: const OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(10)),
                          ),
                          suffixIcon: InkWell(
                            onTap: () {},
                            child: Container(
                              padding:
                                  const EdgeInsets.all(defaultPadding * 0.75),
                              margin: const EdgeInsets.symmetric(
                                  horizontal: defaultPadding / 2),
                              decoration: const BoxDecoration(
                                color: primaryColor,
                                borderRadius:
                                    BorderRadius.all(Radius.circular(10)),
                              ),
                              child: const Icon(Icons.password),
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 32),
                      ElevatedButton.icon(
                        style: TextButton.styleFrom(
                          disabledBackgroundColor: Colors.grey,
                          padding: const EdgeInsets.symmetric(
                            horizontal: 32,
                            vertical: 16,
                          ),
                        ),
                        onPressed: () async {
                          // if (_formKey.currentState!.validate()) {
                          //   authController.register(
                          //       _emailController.text.trim(),
                          //       _passwordController.text.trim(),
                          //       _nameController.text.trim(),
                          //       _lastNameController.text.trim(),
                          //       _phoneController.text.trim());
                          // }
                        },
                        icon: const Icon(Icons.login),
                        label: Text("Créer un compte"),
                      ),
                      const SizedBox(
                        height: 32,
                      ),
                      Text("J'ai déjà un compte",
                          style: Theme.of(context)
                              .textTheme
                              .subtitle1
                              ?.copyWith(
                                  color: Colors.white.withOpacity(0.5),
                                  fontSize: 12)),
                      GestureDetector(
                        onTap: () {
                          Get.offAllNamed(Routes.SIGNIN);
                        },
                        child: Text("Se connecter",
                            style: Theme.of(context)
                                .textTheme
                                .subtitle1
                                ?.copyWith(
                                    color: Colors.white,
                                    fontSize: 12,
                                    decoration: TextDecoration.underline)),
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
