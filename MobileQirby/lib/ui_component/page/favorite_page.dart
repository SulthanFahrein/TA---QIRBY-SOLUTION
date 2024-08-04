import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:provider/provider.dart';
import 'package:test_ta_1/core/config/app_asset.dart';
import 'package:test_ta_1/core/service/c_favorite.dart';
import 'package:test_ta_1/core/service/c_home.dart';
import 'package:test_ta_1/core/service/sessionProvider.dart';
import 'package:test_ta_1/core/model/property.dart' as PropertyModel;
import 'package:test_ta_1/ui_component/widget/alert_component/page_alert.dart';
import 'package:test_ta_1/ui_component/widget/card_component/property_card.dart'; // Import your PropertyCard widget

class FavoritePage extends StatefulWidget {
  @override
  State<FavoritePage> createState() => _FavoritePageState();
}

class _FavoritePageState extends State<FavoritePage> {
  late Future<List<dynamic>> _favoritesFuture;

  @override
  void initState() {
    super.initState();
    _loadFavorites();
  }

  void _loadFavorites() {
    final sessionProvider = Provider.of<SessionProvider>(context, listen: false);
    final user = sessionProvider.user;
    setState(() {
      _favoritesFuture = FavoriteController().getFavorite(user?.idUser ?? 0);
    });
  }

  Future<void> _refreshFavorites() async {
    _loadFavorites();
  }

  @override
  Widget build(BuildContext context) {
    final cHome = Get.put(CHome());
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.transparent,
        foregroundColor: Colors.black,
        title: const Text(
          "Favorites",
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        centerTitle: true,
      ),
      body: RefreshIndicator(
        onRefresh: _refreshFavorites,
        child: FutureBuilder<List<dynamic>>(
          future: _favoritesFuture,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              final error = snapshot.error;
              print('Failed to load favorites: $error');
              return const Center(child: Text('Failed to load favorites'));
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return PageAlert(
                cHome: cHome,
                iconAsset: AppAsset.iconLove2,
                noPropertyText: 'Oops, You Don\'t Have Any Favorite',
                findPropertyText: 'Let\'s Find Your House to fill your Favorite',
              );
            } else {
              final favorites = snapshot.data!
                ..sort((a, b) => b['id_favorite'].compareTo(a['id_favorite']));
              return ListView.builder(
                itemCount: favorites.length,
                itemBuilder: (context, index) {
                  final favorite = favorites[index];
                  final property = PropertyModel.Datum.fromJson(favorite['properti']);
                  return PropertyCard(property: property);
                },
              );
            }
          },
        ),
      ),
    );
  }
}
