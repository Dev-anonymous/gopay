class UserModel {
  String? userId;
  String? username;
  String? email;
  String? password;
  String? designation;
  String? telephone;
  String? role;
  bool? isActivated;
  List<Map<String, dynamic>>? stations;
  DateTime? createdAt;
  // List<DataModel>? dataStation;

  UserModel(
      {this.userId,
      this.username,
      this.email,
      this.password = "2022@Inte-eco",
      this.designation,
      this.telephone,
      this.role,
      this.stations,
      this.isActivated,
      this.createdAt});

  factory UserModel.fromJson(Map<String, dynamic> snapshot) {
    snapshot;
    return UserModel(
      userId: snapshot["id"],
      username: snapshot['username'],
      email: snapshot['email'],
      designation: snapshot['designation'],
      telephone: snapshot['telephone'],
      role: snapshot['role'],
      stations: snapshot['stations'] is Iterable
          ? List.from(snapshot['stations'])
          : null,
      isActivated: snapshot['isActivated'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      if (username != null) "username": username,
      if (email != null) "email": email,
      if (designation != null) "designation": designation,
      if (telephone != null) "telephone": telephone,
      if (role != null) "role": role,
      if (stations != null) "stations": stations,
      if (isActivated != null) "isActivated": isActivated,
    };
  }
}

class RoleUser {
  String titre;
  String valeur;

  RoleUser({required this.titre, required this.valeur});
}
