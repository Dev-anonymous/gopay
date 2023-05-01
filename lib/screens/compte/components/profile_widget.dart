import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../constants.dart';
import '../../../controllers/auth_controller.dart';
import '../../../models/user_data.dart';

class ProfileWidget extends StatefulWidget {
  ProfileWidget({Key? key}) : super(key: key);

  @override
  State<ProfileWidget> createState() => _ProfileWidgetState();
}

class _ProfileWidgetState extends State<ProfileWidget> {
  AuthController authController = Get.put(AuthController());

  final _formKey = GlobalKey<FormState>();

  final TextEditingController _designationController = TextEditingController();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();

  final roles = <RoleUser>[
    RoleUser(titre: "Utilisateur entreprise", valeur: "user"),
    RoleUser(titre: "Compte principal", valeur: "entreprise"),
    RoleUser(titre: "Super Admin", valeur: "admin"),
  ];

  String? role;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    _designationController.dispose();
    _usernameController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return DecoratedBox(
        decoration: const BoxDecoration(
          borderRadius: BorderRadius.all(Radius.circular(18)),
          color: primaryColor,
        ),
        child: Obx(() => Form(
              key: _formKey,
              child: Padding(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 32.0, vertical: 32),
                  child: Column(children: [
                    Text(
                      "Mon compte",
                      style: Theme.of(context).textTheme.headline6,
                    ),
                    Text("Les informations relatives à votre compte",
                        style: Theme.of(context).textTheme.subtitle1?.copyWith(
                            color: Colors.white.withOpacity(0.5),
                            fontSize: 12)),
                    const SizedBox(height: 32),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text("Designation du client"),
                    ),
                    TextFormField(
                      controller: _designationController,
                      keyboardType: TextInputType.text,
                      readOnly: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: authController.currentAccount!.designation,
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    const SizedBox(height: defaultPadding),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text(
                        "Nom d\'utilisateur du client",
                      ),
                    ),
                    TextFormField(
                      controller: _usernameController,
                      keyboardType: TextInputType.text,
                      readOnly: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: authController.currentAccount!.username,
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    const SizedBox(height: defaultPadding),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text("Numero de telephone"),
                    ),
                    TextFormField(
                      controller: _phoneController,
                      keyboardType: TextInputType.phone,
                      readOnly: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: authController.currentAccount!.telephone,
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    const SizedBox(height: defaultPadding),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text("Adresse email"),
                    ),
                    TextFormField(
                      controller: _emailController,
                      keyboardType: TextInputType.emailAddress,
                      readOnly: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: authController.currentAccount!.email,
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    const SizedBox(height: defaultPadding),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text("Mot de passe"),
                    ),
                    TextFormField(
                      controller: _passwordController,
                      keyboardType: TextInputType.visiblePassword,
                      readOnly: true,
                      obscureText: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: "Mot de passe",
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    const SizedBox(height: defaultPadding),
                    Align(
                      alignment: Alignment.topLeft,
                      child: Text("Role du compte"),
                    ),
                    TextFormField(
                      keyboardType: TextInputType.text,
                      readOnly: true,
                      enabled: false,
                      decoration: InputDecoration(
                        hintText: authController.currentAccount!.role,
                        fillColor: bgColor,
                        filled: true,
                        border: OutlineInputBorder(
                          borderSide: BorderSide.none,
                          borderRadius: BorderRadius.all(Radius.circular(10)),
                        ),
                      ),
                    ),
                    SizedBox(height: 32),
                  ])),
            )));
  }

  DropdownMenuItem<String> _buildMenuItem(RoleUser item) {
    return DropdownMenuItem<String>(
        value: item.valeur,
        child: Text(item.titre,
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500)));
  }
}
