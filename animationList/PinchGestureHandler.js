import React from 'react'
import { View, Text, StyleSheet, Image, Dimensions } from 'react-native';
import { PinchGestureHandler, GestureHandlerRootView, PinchGestureHandlerGestureEvent } from 'react-native-gesture-handler'
import Animated, { useAnimatedGestureHandler, useAnimatedStyle, useSharedValue, withTiming } from 'react-native-reanimated';


const imageUri = 'https://images.unsplash.com/photo-1621569642780-4864752e847e?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=668&q=80';

const {width, height} = Dimensions.get('window');

export default function PinchGestureHandlerScreen() {

    const scale = useSharedValue(1);

    const focalX = useSharedValue(0);
    const focalY = useSharedValue(0);

    const pinchHandler = useAnimatedGestureHandler({
        onActive : (event) => {
            scale.value = event.scale;
            focalX.value = event.focalX;
            focalY.value = event.focalY;
        },
        onEnd : () => {
            scale.value = withTiming(1);
        }
    });

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                {translateX : focalX.value},
                {translateY : focalY.value},
                {translateX : -width / 2},
                {translateY : -height / 2},
                {scale : scale.value},
                {translateX : -focalX.value},
                {translateY : -focalY.value},
                {translateX : width / 2},
                {translateY : height / 2},

            ]
        };

    });

    const animatedFocalStyle = useAnimatedStyle(() => {

        return {
            transform : [
                {translateX : focalX.value},
                {translateY : focalY.value}
            ]
        }
    });


    return (
        <GestureHandlerRootView style={{flex:1}}>
            <PinchGestureHandler style={styles.container} onGestureEvent={pinchHandler}>
                <Animated.View style={{flex : 1}}>
                    <Animated.Image 
                        source={{uri:imageUri}} 
                        style={[{height:'100%',width:'100%', flex:1}, animatedStyle]}
                    />
                    <Animated.View style={[styles.focalPoint, animatedFocalStyle]} />
                </Animated.View>
            </PinchGestureHandler>
        </GestureHandlerRootView>
    )   
}

const styles = StyleSheet.create({
    container : {
        //borderWidth :12 , 
        //borderColor:'black',
        flex : 1,
        backgroundColor : '#FFF',
        alignItems : 'center',
        justifyContent : 'center'
    },
    focalPoint : {
        ...StyleSheet.absoluteFillObject,
        width : 20,
        height : 20,
        backgroundColor : 'blue',
        borderRadius : 10,
    }
});