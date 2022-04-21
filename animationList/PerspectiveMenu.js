import React, { useCallback } from 'react'
import { Dimensions, SafeAreaView, StatusBar, StyleSheet, Text, View } from 'react-native'
import { GestureHandlerRootView, PanGestureHandler } from 'react-native-gesture-handler'
import Animated, { Extrapolate, interpolate, useAnimatedGestureHandler, useAnimatedStyle, useSharedValue, withTiming } from 'react-native-reanimated'
import Icon from 'react-native-vector-icons/FontAwesome';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

const THRESHOLD = SCREEN_WIDTH / 3;

const PerspectiveMenu = () => {

    const translateX = useSharedValue(0);

    const panGestureEvent = useAnimatedGestureHandler({
        onStart : (_, context) => { //context use to store previous value
            context.x = translateX.value;
        },
        onActive : (event, context) => {
            translateX.value = event.translationX + context.x;
        },
        onEnd : () => {

            if(translateX.value <= THRESHOLD){
                translateX.value = withTiming(0);
            }
            else{
                translateX.value = withTiming(SCREEN_WIDTH / 2);
            }

            
        }
    });

    const animatedStyle = useAnimatedStyle(() => {

        const rotate = interpolate(
            translateX.value,
            [0, SCREEN_WIDTH/2],
            [0, 3],
            Extrapolate.CLAMP
        );

        const borderRadius = interpolate(
            translateX.value,
            [0, SCREEN_WIDTH / 2],
            [0, 15],
            Extrapolate.CLAMP
        );

        return {
            borderRadius,
            transform : [
                { perspective : 100 },
                {translateX : translateX.value },
                {rotateY : `-${rotate}deg` }
            ]
        }
    });

    const onPress = useCallback(() => {

        if(translateX.value > 0){
            translateX.value = withTiming(0);
        }
        else{
            translateX.value = withTiming(SCREEN_WIDTH / 2);
        }

    },[]);

    return (
        <GestureHandlerRootView style={{flex: 1}}>
            <SafeAreaView style={styles.container}>
                <StatusBar style="inverted" />
                <PanGestureHandler onGestureEvent={panGestureEvent}>
                    <Animated.View style={[{backgroundColor:'white', flex: 1}, animatedStyle]}>
                        <Icon 
                            name="bars" 
                            size={32} 
                            color={BACKGROUND_COLOR} 
                            style={{margin : 15}}
                            onPress={onPress}
                        />
                    </Animated.View>
                </PanGestureHandler>
            </SafeAreaView>
        </GestureHandlerRootView>
    )
}

export default PerspectiveMenu;

const BACKGROUND_COLOR = '#1e1e23';

const styles = StyleSheet.create({
    container : {
        flex : 1,
        backgroundColor : BACKGROUND_COLOR,
    }
})